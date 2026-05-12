<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Показать страницу профиля
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $activeTab = $request->get('tab', 'bookings');

        $bookingFilters = [
            'search' => $request->get('booking_search'),
            'category' => $request->get('booking_category'),
            'date_from' => $request->get('booking_date_from'),
            'date_to' => $request->get('booking_date_to'),
            'sort' => $request->get('booking_sort', 'newest'),
        ];

        $reviewFilters = [
            'search' => $request->get('review_search'),
            'category' => $request->get('review_category'),
            'date_from' => $request->get('review_date_from'),
            'date_to' => $request->get('review_date_to'),
            'sort' => $request->get('review_sort', 'newest'),
        ];

        $favoriteFilters = [
            'search' => $request->get('favorite_search'),
            'category' => $request->get('favorite_category'),
            'date_from' => $request->get('favorite_date_from'),
            'date_to' => $request->get('favorite_date_to'),
            'sort' => $request->get('favorite_sort', 'newest'),
        ];

        $bookingsQuery = $user->bookings()->with(['trip', 'item']);

        if ($bookingFilters['search']) {
            $search = $bookingFilters['search'];
            $bookingsQuery->where(function ($query) use ($search) {
                $query->where('comment', 'like', "%{$search}%")
                    ->orWhereHas('trip', function ($tripQuery) use ($search) {
                        $tripQuery->where('title', 'like', "%{$search}%")
                            ->orWhere('activity_type', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    })
                    ->orWhereHas('item', function ($itemQuery) use ($search) {
                        $itemQuery->where('title', 'like', "%{$search}%")
                            ->orWhere('activity_type', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
            });
        }

        if ($bookingFilters['category']) {
            $category = $bookingFilters['category'];
            $bookingsQuery->where(function ($query) use ($category) {
                $query->whereHas('trip', fn ($tripQuery) => $tripQuery->where('activity_type', $category))
                    ->orWhereHas('item', fn ($itemQuery) => $itemQuery->where('activity_type', $category));
            });
        }

        if ($bookingFilters['date_from']) {
            $bookingsQuery->whereDate('booking_date', '>=', $bookingFilters['date_from']);
        }

        if ($bookingFilters['date_to']) {
            $bookingsQuery->whereDate('booking_date', '<=', $bookingFilters['date_to']);
        }

        match ($bookingFilters['sort']) {
            'oldest' => $bookingsQuery->orderBy('created_at'),
            'date_asc' => $bookingsQuery->orderBy('booking_date'),
            'date_desc' => $bookingsQuery->orderByDesc('booking_date'),
            'price_asc' => $bookingsQuery->orderBy('total_price'),
            'price_desc' => $bookingsQuery->orderByDesc('total_price'),
            default => $bookingsQuery->orderByDesc('created_at'),
        };

        $reviewsQuery = $user->reviews()->with(['item', 'trip']);

        if ($reviewFilters['search']) {
            $search = $reviewFilters['search'];
            $reviewsQuery->where(function ($query) use ($search) {
                $query->where('text', 'like', "%{$search}%")
                    ->orWhereHas('item', function ($itemQuery) use ($search) {
                        $itemQuery->where('title', 'like', "%{$search}%")
                            ->orWhere('activity_type', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    })
                    ->orWhereHas('trip', function ($tripQuery) use ($search) {
                        $tripQuery->where('title', 'like', "%{$search}%")
                            ->orWhere('activity_type', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
            });
        }

        if ($reviewFilters['category']) {
            $category = $reviewFilters['category'];
            $reviewsQuery->where(function ($query) use ($category) {
                $query->whereHas('item', fn ($itemQuery) => $itemQuery->where('activity_type', $category))
                    ->orWhereHas('trip', fn ($tripQuery) => $tripQuery->where('activity_type', $category));
            });
        }

        if ($reviewFilters['date_from']) {
            $reviewsQuery->whereDate('created_at', '>=', $reviewFilters['date_from']);
        }

        if ($reviewFilters['date_to']) {
            $reviewsQuery->whereDate('created_at', '<=', $reviewFilters['date_to']);
        }

        match ($reviewFilters['sort']) {
            'oldest' => $reviewsQuery->orderBy('created_at'),
            'rating_asc' => $reviewsQuery->orderBy('rating'),
            'rating_desc' => $reviewsQuery->orderByDesc('rating'),
            'popular' => $reviewsQuery->orderByDesc('likes'),
            default => $reviewsQuery->orderByDesc('created_at'),
        };

        $favoritesQuery = $user->favoriteItems();

        if ($favoriteFilters['search']) {
            $search = $favoriteFilters['search'];
            $favoritesQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('activity_type', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($favoriteFilters['category']) {
            $favoritesQuery->where('activity_type', $favoriteFilters['category']);
        }

        if ($favoriteFilters['date_from']) {
            $favoritesQuery->wherePivot('created_at', '>=', $favoriteFilters['date_from']);
        }

        if ($favoriteFilters['date_to']) {
            $favoritesQuery->wherePivot('created_at', '<=', $favoriteFilters['date_to'] . ' 23:59:59');
        }

        match ($favoriteFilters['sort']) {
            'oldest' => $favoritesQuery->orderByPivot('created_at'),
            'price_asc' => $favoritesQuery->orderBy('price'),
            'price_desc' => $favoritesQuery->orderByDesc('price'),
            'title_asc' => $favoritesQuery->orderBy('title'),
            default => $favoritesQuery->orderByPivot('created_at', 'desc'),
        };

        $bookings = $bookingsQuery->paginate(10);
        $reviews = $reviewsQuery->paginate(10);
        $favorites = $favoritesQuery->paginate(10);

        $bookingCategories = Trip::query()
            ->whereNotNull('activity_type')
            ->pluck('activity_type')
            ->merge(Item::query()->whereNotNull('activity_type')->pluck('activity_type'))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $reviewCategories = $bookingCategories;
        $favoriteCategories = Item::query()
            ->whereNotNull('activity_type')
            ->pluck('activity_type')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('profile', compact(
            'user',
            'bookings',
            'reviews',
            'favorites',
            'activeTab',
            'bookingFilters',
            'reviewFilters',
            'favoriteFilters',
            'bookingCategories',
            'reviewCategories',
            'favoriteCategories'
        ));
    }

    /**
     * Загрузка аватарки
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Удаляем старую аватарку
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Сохраняем новую
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        return back()->with('success', 'Аватарка загружена');
    }

    /**
     * Удаление аватарки
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'login' => 'required|string|max:255|unique:users,login,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($data);

        return back()->with('success', 'Профиль обновлён');
    }

    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
            $user->save();
        }

        return back()->with('success', 'Аватарка удалена');
    }
}
