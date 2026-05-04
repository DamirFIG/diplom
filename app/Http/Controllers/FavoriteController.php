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
        $item = Item::findOrFail($itemId);
        
        // Проверяем, не добавлено ли уже в избранное
        $exists = $user->favorites()->where('item_id', $itemId)->exists();
        
        if (!$exists) {
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
        
        $item = Item::findOrFail($itemId);
        
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
