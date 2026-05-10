<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\Item;
use App\Models\Trip;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // АРЕНДА (каталог)
        $itemsQuery = Item::query();

        // 🔍 ПОИСК
        if ($request->filled('search')) {
            $itemsQuery->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        // 🎯 ФИЛЬТР ПО ТИПУ АКТИВНОСТИ
        if ($request->filled('activity_type')) {
            $itemsQuery->where('activity_type', $request->activity_type);
        }

        // 🔃 СОРТИРОВКА
        if ($request->sort === 'price_asc') {
            $itemsQuery->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $itemsQuery->orderBy('price', 'desc');
        } else {
            $itemsQuery->orderBy('id', 'desc');
        }

        // 📦 ПАГИНАЦИЯ аренды
        $items = $itemsQuery->paginate(8)->withQueryString();

        // 🚀 ПОЕЗДКИ (новая таблица trips)
        $tripsQuery = Trip::query();

        // 🔍 ПОИСК для поездок
        if ($request->filled('trip_search')) {
            $tripsQuery->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->trip_search.'%')
                    ->orWhere('description', 'like', '%'.$request->trip_search.'%');
            });
        }

        // 💰 ФИЛЬТР ПО ЦЕНЕ (от и до)
        if ($request->filled('trip_price_from')) {
            $tripsQuery->where('price', '>=', $request->trip_price_from);
        }
        if ($request->filled('trip_price_to')) {
            $tripsQuery->where('price', '<=', $request->trip_price_to);
        }

        // 📅 ФИЛЬТР ПО ДАТЕ
        if ($request->filled('trip_date_from')) {
            $tripsQuery->where('event_date', '>=', $request->trip_date_from);
        }
        if ($request->filled('trip_date_to')) {
            $tripsQuery->where('event_date', '<=', $request->trip_date_to);
        }

        // 👨‍✈️ ФИЛЬТР ПО ГИДУ
        if ($request->filled('trip_guide')) {
            $tripsQuery->where('guide_id', $request->trip_guide);
        }

        // 🎯 ФИЛЬТР ПО ТИПУ АКТИВНОСТИ для поездок
        if ($request->filled('trip_activity_type')) {
            $tripsQuery->where('activity_type', $request->trip_activity_type);
        }

        // 🔃 СОРТИРОВКА для поездок
        if ($request->trip_sort === 'price_asc') {
            $tripsQuery->orderBy('price', 'asc');
        } elseif ($request->trip_sort === 'price_desc') {
            $tripsQuery->orderBy('price', 'desc');
        } elseif ($request->trip_sort === 'date_asc') {
            $tripsQuery->orderBy('event_date', 'asc');
        } elseif ($request->trip_sort === 'date_desc') {
            $tripsQuery->orderBy('event_date', 'desc');
        } else {
            $tripsQuery->orderBy('event_date', 'asc');
        }

        // 📦 ПАГИНАЦИЯ поездок
        $trips = $tripsQuery->paginate(8)->withQueryString();

        // 👨‍✈️ ГИДЫ
        $guides = Guide::all();

        return view('home', compact('items', 'trips', 'guides'));
    }

    public function searchItems(Request $request)
    {
        $itemsQuery = Item::query();

        // 🔍 ПОИСК
        if ($request->filled('search')) {
            $itemsQuery->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        // 🎯 ФИЛЬТР ПО ТИПУ АКТИВНОСТИ
        if ($request->filled('activity_type')) {
            $itemsQuery->where('activity_type', $request->activity_type);
        }

        // 🔃 СОРТИРОВКА
        if ($request->sort === 'price_asc') {
            $itemsQuery->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $itemsQuery->orderBy('price', 'desc');
        } else {
            $itemsQuery->orderBy('id', 'desc');
        }

        $items = $itemsQuery->paginate(8)->withQueryString();

        // Если это AJAX-запрос, возвращаем только карточки с пагинацией
        if ($request->ajax()) {
            return response()->json([
                'content' => view('home.partials.items-list', compact('items'))->render(),
                'pagination' => view('home.partials.pagination', [
                    'paginator' => $items,
                    'anchor' => 'catalog',
                ])->render(),
            ]);
        }

        return view('home', compact('items'));
    }

    public function searchTrips(Request $request)
    {
        $tripsQuery = Trip::query();

        // 🔍 ПОИСК
        if ($request->filled('trip_search')) {
            $tripsQuery->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->trip_search.'%')
                    ->orWhere('description', 'like', '%'.$request->trip_search.'%');
            });
        }

        // 💰 ФИЛЬТР ПО ЦЕНЕ (от и до)
        if ($request->filled('trip_price_from')) {
            $tripsQuery->where('price', '>=', $request->trip_price_from);
        }
        if ($request->filled('trip_price_to')) {
            $tripsQuery->where('price', '<=', $request->trip_price_to);
        }

        // 📅 ФИЛЬТР ПО ДАТЕ
        if ($request->filled('trip_date_from')) {
            $tripsQuery->where('event_date', '>=', $request->trip_date_from);
        }
        if ($request->filled('trip_date_to')) {
            $tripsQuery->where('event_date', '<=', $request->trip_date_to);
        }

        // 👨‍✈️ ФИЛЬТР ПО ГИДУ
        if ($request->filled('trip_guide')) {
            $tripsQuery->where('guide_id', $request->trip_guide);
        }

        // 🎯 ФИЛЬТР ПО ТИПУ АКТИВНОСТИ
        if ($request->filled('trip_activity_type')) {
            $tripsQuery->where('activity_type', $request->trip_activity_type);
        }

        // 🔃 СОРТИРОВКА
        if ($request->trip_sort === 'price_asc') {
            $tripsQuery->orderBy('price', 'asc');
        } elseif ($request->trip_sort === 'price_desc') {
            $tripsQuery->orderBy('price', 'desc');
        } elseif ($request->trip_sort === 'date_asc') {
            $tripsQuery->orderBy('event_date', 'asc');
        } elseif ($request->trip_sort === 'date_desc') {
            $tripsQuery->orderBy('event_date', 'desc');
        } else {
            $tripsQuery->orderBy('event_date', 'asc');
        }

        $trips = $tripsQuery->paginate(8)->withQueryString();

        // Если это AJAX-запрос, возвращаем только карточки с пагинацией
        if ($request->ajax()) {
            return response()->json([
                'content' => view('home.partials.trips-list', compact('trips'))->render(),
                'pagination' => view('home.partials.pagination', [
                    'paginator' => $trips,
                    'anchor' => 'trips',
                ])->render(),
            ]);
        }

        return view('home', compact('trips'));
    }
}
