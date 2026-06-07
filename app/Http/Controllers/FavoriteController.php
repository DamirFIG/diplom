<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Добавить в избранное
     */
    public function add($itemId)
    {
        $user = Auth::user();

        // Проверяем, существует ли элемент
        Item::findOrFail($itemId);

        // Проверяем, не добавлено ли уже в избранное
        $exists = $user->favorites()->where('item_id', $itemId)->exists();

        if (! $exists) {
            $user->favorites()->create([
                'item_id' => $itemId,
            ]);
        }

        return response()->json([
            'success' => true,
            'is_favorite' => true,
        ]);
    }

    /**
     * Удалить из избранного
     */
    public function remove($itemId)
    {
        $user = Auth::user();

        $favorite = $user->favorites()->where('item_id', $itemId)->first();

        if ($favorite) {
            $favorite->delete();
        }

        return response()->json([
            'success' => true,
            'is_favorite' => false,
        ]);
    }

    /**
     * Переключить статус избранного
     */
    public function toggle($itemId)
    {
        $user = Auth::user();

        Item::findOrFail($itemId);

        $exists = $user->favorites()->where('item_id', $itemId)->exists();

        if ($exists) {
            $user->favorites()->where('item_id', $itemId)->delete();
            $isFavorite = false;
        } else {
            $user->favorites()->create([
                'item_id' => $itemId,
            ]);
            $isFavorite = true;
        }

        return response()->json([
            'success' => true,
            'is_favorite' => $isFavorite,
        ]);
    }

    /**
     * Проверить сразу несколько элементов избранного.
     */
    public function checkMany(Request $request)
    {
        $ids = collect(explode(',', (string) $request->query('ids')))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return response()->json([
                'favorites' => [],
            ]);
        }

        $favoriteIds = $request->user()
            ->favorites()
            ->whereIn('item_id', $ids)
            ->pluck('item_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return response()->json([
            'favorites' => $favoriteIds,
        ]);
    }

    /**
     * Проверить, является ли элемент избранным
     */
    public function check($itemId)
    {
        $user = Auth::user();

        $isFavorite = $user->favorites()->where('item_id', $itemId)->exists();

        return response()->json([
            'is_favorite' => $isFavorite,
        ]);
    }
}
