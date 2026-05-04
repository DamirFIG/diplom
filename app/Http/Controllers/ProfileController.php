<?php

namespace App\Http\Controllers;

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

        // Загружаем бронирования с поездками (пагинация)
        $bookings = $user->bookings()
            ->with(['trip','item'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Загружаем отзывы (пагинация)
        $reviews = $user->reviews()
            ->with('item')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Загружаем избранные элементы (пагинация)
        $favorites = $user->favoriteItems()->paginate(10);

        // Активная вкладка
        $activeTab = $request->get('tab', 'bookings');

        return view('profile', compact('user', 'bookings', 'reviews', 'favorites', 'activeTab'));
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
