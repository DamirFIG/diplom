<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\Item;
use App\Models\Trip;
use App\Models\User;
use App\Models\Route;
use App\Models\RoutePoint;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'items' => Item::all(),
            'trips' => Trip::all(),
            'guides' => Guide::all(),
            'users' => User::where('role', '!=', 'admin')->get(),
        ]);
    }

    // Карточки (Items)
    public function items(Request $request)
    {
        $query = Item::query();
        
        // Поиск
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        // Фильтр по типу активности
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }
        
        // Сортировка
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        $items = $query->get();
        
        return view('admin.items.index', [
            'items' => $items,
            'activityTypes' => ['гидроцикл', 'банан', 'флайборд', 'сапборд', 'катамаран'],
        ]);
    }

    // Поездки (Trips)
    public function trips(Request $request)
    {
        $query = Trip::query();
        
        // Поиск
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        // Фильтр по типу активности
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }
        
        // Сортировка
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        $trips = $query->get();
        
        return view('admin.trips.index', [
            'trips' => $trips,
            'activityTypes' => ['гидроцикл', 'банан', 'флайборд', 'сапборд', 'катамаран'],
        ]);
    }

    public function users(Request $request)
    {
        $query = User::where('role', '!=', 'admin');
        
        // Поиск
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('login', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        // Фильтр по роли
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Сортировка
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        $users = $query->get();
        
        return view('admin.users', [
            'users' => $users,
        ]);
    }

    public function banUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Нельзя забанить администратора');
        }

        return $user->ban()
            ? back()->with('success', "Пользователь {$user->login} забанен")
            : back()->with('error', 'Ошибка при бане пользователя');
    }

    public function unbanUser($id)
    {
        $user = User::findOrFail($id);

        return $user->unban()
            ? back()->with('success', "Пользователь {$user->login} разбанен")
            : back()->with('error', 'Ошибка при разбане пользователя');
    }

    public function create()
    {
        return view('admin.create', [
            'guides' => Guide::all(),
        ]);
    }

    public function createTrip()
    {
        return view('admin.trips.create', [
            'guides' => Guide::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateCard($request);

        DB::beginTransaction();
        try {
            $galleryPaths = $this->uploadImages($request->file('gallery'));

            if (empty($galleryPaths)) {
                return back()->withInput()->with('error', 'Необходимо загрузить хотя бы одно изображение');
            }

            $data['gallery'] = $galleryPaths;

            $item = $this->createOrUpdateCard($data, null, $request);

            DB::commit();

            $route = $item instanceof Trip ? 'admin.trips' : 'admin.items';
            return redirect()->route($route)->with('success', 'Карточка создана');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->deleteImages($data['gallery'] ?? []);
            return back()->withInput()->with('error', 'Ошибка: ' . $e->getMessage());
        }
    }

    public function storeTrip(Request $request)
    {
        $data = $this->validateCard($request);

        DB::beginTransaction();
        try {
            $galleryPaths = $this->uploadImages($request->file('gallery'));

            if (empty($galleryPaths)) {
                return back()->withInput()->with('error', 'Необходимо загрузить хотя бы одно изображение');
            }

            $data['gallery'] = $galleryPaths;

            $trip = $this->createOrUpdateCard($data, null, $request);

            DB::commit();

            return redirect()->route('admin.trips')->with('success', 'Поездка создана');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->deleteImages($data['gallery'] ?? []);
            return back()->withInput()->with('error', 'Ошибка: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $item = $this->findModel($id);
        $guides = Guide::withCount('trips')->orderByDesc('id')->limit(10)->get();
        
        // Если это Trip, используем отдельный view
        if ($item instanceof Trip) {
            return view('admin.trips.edit', compact('item', 'guides'));
        }
        
        return view('admin.items.edit', compact('item', 'guides'));
    }

    public function update(Request $request, $id)
    {
        $item = $this->findModel($id);
        $data = $this->validateCard($request);

        DB::beginTransaction();
        try {
            $existingGallery = $item->gallery ?? [];

            if (!empty($data['delete_images'])) {
                foreach ($data['delete_images'] as $imageToDelete) {
                    $key = array_search($imageToDelete, $existingGallery);
                    if ($key !== false) {
                        Storage::disk('public')->delete($imageToDelete);
                        unset($existingGallery[$key]);
                    }
                }
                $existingGallery = array_values($existingGallery);
            }

            if ($request->hasFile('gallery')) {
                $newImages = $this->uploadImages($request->file('gallery'));
                $existingGallery = array_merge($existingGallery, $newImages);
            }

            $data['gallery'] = $existingGallery;

            $this->createOrUpdateCard($data, $item, $request);

            DB::commit();
            
            $route = $item instanceof Trip ? 'admin.trips' : 'admin.items';
            return redirect()->route($route)->with('success', 'Карточка обновлена');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ошибка: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $item = $this->findModel($id);

        if ($item->gallery && is_array($item->gallery)) {
            $this->deleteImages($item->gallery);
        }

        $item->delete();

        $route = $item instanceof Trip ? 'admin.trips' : 'admin.items';
        return redirect()->route($route)->with('success', 'Карточка удалена');
    }

    private function findModel($id): Item|Trip
    {
        return Item::find($id) ?? Trip::findOrFail($id);
    }

    private function validateCard(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string',
            'activity_type' => 'required|string',
            'price' => 'required|integer',
            'event_date' => 'nullable|date',
            'description' => 'nullable|string',
            'max_people' => 'nullable|integer',
            'min_age' => 'nullable|integer',
            'duration_minutes' => 'nullable|integer',
            'guide_id' => 'nullable|exists:guides,id',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'delete_images' => 'nullable|array',
            'type' => 'required|in:transport,route',
            'route_title' => 'nullable|string',
            'route_description' => 'nullable|string',
            'start_lat' => 'nullable|numeric',
            'start_lng' => 'nullable|numeric',
            'end_lat' => 'nullable|numeric',
            'end_lng' => 'nullable|numeric',
            'route_points' => 'nullable|array',
        ]);
    }

    private function uploadImages(?array $files): array
    {
        $paths = [];
        if ($files) {
            foreach ($files as $file) {
                try {
                    $path = $file->store('img', 'public');
                    if ($path) {
                        $paths[] = $path;
                    }
                } catch (\Exception $e) {
                    throw $e;
                }
            }
        }
        return $paths;
    }

    private function deleteImages(array $paths): void
    {
        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function createOrUpdateCard(array $data, ?Model $item = null, Request $request): Item|Trip|null
    {
        if ($data['type'] === 'transport') {
            unset($data['guide_id'], $data['event_date']);
            return $item
                ? tap($item)->update($data)
                : Item::create($data);
        }

        $tripData = Arr::only($data, [
            'title', 'activity_type', 'price', 'description',
            'max_people', 'min_age', 'duration_minutes',
            'guide_id', 'event_date', 'gallery',
        ]);

        if ($item instanceof Trip) {
            $item->update($tripData);
            $this->updateRoute($item, $data, $request);
            return $item;
        }

        $trip = Trip::create($tripData);
        
        // Создаем маршрут только если есть данные о нем
        if ($request->has('start_lat') || $request->has('end_lat') || $request->has('route_points')) {
            $this->createRoute($trip, $data, $request);
        }
        
        return $trip;
    }

    private function createRoute(Trip $trip, array $data, Request $request): void
    {
        // Создаем маршрут только если есть точки
        if (!empty($data['route_points'])) {
            $route = Route::create([
                'trip_id' => $trip->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'start_lat' => null,
                'start_lng' => null,
                'end_lat' => null,
                'end_lng' => null,
            ]);

            $this->syncRoutePoints($route, $data['route_points']);
            $trip->update(['route_id' => $route->id]);
        }
    }

    private function updateRoute(Trip $trip, array $data, Request $request): void
    {
        $route = $trip->route;
        if ($route) {
            $route->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'start_lat' => null,
                'start_lng' => null,
                'end_lat' => null,
                'end_lng' => null,
            ]);

            $route->points()->delete();
            $this->syncRoutePoints($route, $data['route_points'] ?? []);
        }
    }

    private function syncRoutePoints(Route $route, array $points): void
    {
        foreach ($points as $index => $pointData) {
            // Для точек поворота координаты не требуются
            if (empty($pointData['lat']) || empty($pointData['lng'])) {
                continue;
            }

            $imagePath = null;
            if (!empty($pointData['image_file']) && $pointData['image_file'] instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $pointData['image_file']->store('img/route_points', 'public');
            } elseif (!empty($pointData['existing_image'])) {
                $imagePath = $pointData['existing_image'];
            }

            // Определяем тип точки и её видимость
            $type = $pointData['type'] ?? 'stop';
            $isVisible = $type !== 'turn' && ($pointData['is_visible'] ?? true);

            RoutePoint::create([
                'route_id' => $route->id,
                'title' => $pointData['title'] ?? 'Остановка ' . ($index + 1),
                'description' => $pointData['description'] ?? '',
                'lat' => $pointData['lat'],
                'lng' => $pointData['lng'],
                'image' => $imagePath,
                'sort_order' => $index,
                'is_visible' => $isVisible,
                'type' => $type,
            ]);
        }
    }

    // Гиды
    public function guides(Request $request)
    {
        $query = Guide::withCount('trips');
        
        // Поиск
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Сортировка
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        $guides = $query->orderByDesc('id')->get();
        
        return view('admin.guides.index', [
            'guides' => $guides,
        ]);
    }

    public function createGuide()
    {
        return view('admin.guides.create');
    }

    public function storeGuide(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('img/guides', 'public');
        }

        Guide::create($data);

        return redirect()->route('admin.guides')->with('success', 'Гид создан');
    }

    public function editGuide($id)
    {
        return view('admin.guides.edit', [
            'guide' => Guide::findOrFail($id),
        ]);
    }

    public function updateGuide(Request $request, $id)
    {
        $guide = Guide::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            // Удаляем старое фото
            if ($guide->photo) {
                Storage::disk('public')->delete($guide->photo);
            }
            $data['photo'] = $request->file('photo')->store('img/guides', 'public');
        }

        $guide->update($data);

        return redirect()->route('admin.guides')->with('success', 'Гид обновлён');
    }

    public function destroyGuide($id)
    {
        $guide = Guide::findOrFail($id);

        // Проверяем, есть ли trips у гида
        if ($guide->trips()->count() > 0) {
            return back()->with('error', 'Нельзя удалить гида, у которого есть поездки');
        }

        if ($guide->photo) {
            Storage::disk('public')->delete($guide->photo);
        }

        $guide->delete();

        return redirect()->route('admin.guides')->with('success', 'Гид удалён');
    }
}
