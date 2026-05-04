<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function show($id)
    {
        $item = Item::with(['reviews.user' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        $reviews = $item->reviews()->with('user')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('items.show', compact('item', 'reviews'));
    }
}
