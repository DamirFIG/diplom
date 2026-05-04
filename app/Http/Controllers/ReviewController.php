<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->back();
        }

        $request->validate([
            'text' => 'required|string|max:256',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'text' => $request->text,
            'rating' => $request->rating,
        ];

        if ($request->has('item_id')) {
            $data['item_id'] = $request->item_id;
        }

        if ($request->has('trip_id')) {
            $data['trip_id'] = $request->trip_id;
        }

        Review::create($data);

        if ($request->item_id) {
            return redirect()->route('items.show', $request->item_id);
        }

        if ($request->trip_id) {
            return redirect()->route('trips.show', $request->trip_id);
        }

        // Если отзыв создан из профиля, возвращаем туда же
        if ($request->has('from_profile')) {
            return redirect()->route('profile.index', ['tab' => 'reviews'])->with('success', 'Отзыв успешно добавлен');
        }

        return redirect()->back()->with('success', 'Отзыв успешно добавлен');
    }

    public function reactSimple(Request $request, Review $review)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false], 403);
        }

        $userId = auth()->id();
        $type = $request->input('type');

        $reacted = session("review_{$review->id}_user_{$userId}", null);

        if ($reacted === $type) {
            $type === 'like' ? $review->decrement('likes') : $review->decrement('dislikes');
            session()->forget("review_{$review->id}_user_{$userId}");
            $userReaction = null;
        } else {
            if ($reacted === 'like') $review->decrement('likes');
            if ($reacted === 'dislike') $review->decrement('dislikes');

            $type === 'like' ? $review->increment('likes') : $review->increment('dislikes');

            session(["review_{$review->id}_user_{$userId}" => $type]);
            $userReaction = $type;
        }

        $review->save();

        return response()->json([
            'success' => true,
            'likes' => $review->likes,
            'dislikes' => $review->dislikes,
            'userReaction' => $userReaction,
        ]);
    }
}
