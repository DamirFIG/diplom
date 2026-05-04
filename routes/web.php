<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;

// Админ-панель
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{id}/ban', [AdminController::class, 'banUser'])->name('users.ban');
    Route::post('/users/{id}/unban', [AdminController::class, 'unbanUser'])->name('users.unban');
    
    // Карточки (Items)
    Route::get('/items', [AdminController::class, 'items'])->name('items');
    Route::get('/items/create', [AdminController::class, 'create'])->name('items.create');
    Route::post('/items/store', [AdminController::class, 'store'])->name('items.store');
    Route::get('/items/{id}/edit', [AdminController::class, 'edit'])->name('items.edit');
    Route::put('/items/{id}', [AdminController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [AdminController::class, 'destroy'])->name('items.destroy');
    
    // Поездки (Trips)
    Route::get('/trips', [AdminController::class, 'trips'])->name('trips');
    Route::get('/trips/create', [AdminController::class, 'createTrip'])->name('trips.create');
    Route::post('/trips/store', [AdminController::class, 'storeTrip'])->name('trips.store');
    Route::get('/trips/{id}/edit', [AdminController::class, 'edit'])->name('trips.edit');
    Route::put('/trips/{id}', [AdminController::class, 'update'])->name('trips.update');
    Route::delete('/trips/{id}', [AdminController::class, 'destroy'])->name('trips.destroy');
    
    // Заказы
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::post('/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('bookings.status');

    // Гиды
    Route::get('/guides', [AdminController::class, 'guides'])->name('guides');
    Route::get('/guides/create', [AdminController::class, 'createGuide'])->name('guides.create');
    Route::post('/guides/store', [AdminController::class, 'storeGuide'])->name('guides.store');
    Route::get('/guides/{id}/edit', [AdminController::class, 'editGuide'])->name('guides.edit');
    Route::put('/guides/{id}', [AdminController::class, 'updateGuide'])->name('guides.update');
    Route::delete('/guides/{id}', [AdminController::class, 'destroyGuide'])->name('guides.destroy');
});

// Аутентификация
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search/items', [HomeController::class, 'searchItems'])->name('search.items');
Route::get('/search/trips', [HomeController::class, 'searchTrips'])->name('search.trips');
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('user.register');
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('user.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Восстановление пароля
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('auth.forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('auth.forgot-password.send');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('auth.reset-password');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password.submit');

// Профиль
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('index');
    Route::post('/avatar', [ProfileController::class, 'uploadAvatar'])->name('avatar.upload');
    Route::post('/avatar/delete', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
});

// Отзывы
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::post('/reviews/{review}/react-simple', [ReviewController::class, 'reactSimple'])->name('reviews.react')->middleware('auth');

// Карточки
Route::middleware('auth')->group(function () {
    Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');
    Route::get('/trips/{id}', [TripController::class, 'show'])->name('trips.show');
    Route::post('/trips/book', [TripController::class, 'book'])->name('trips.book');
});

// Избранное
Route::middleware('auth')->group(function () {
    Route::post('/favorites/{id}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/favorites/{id}/add', [FavoriteController::class, 'add'])->name('favorites.add');
    Route::post('/favorites/{id}/remove', [FavoriteController::class, 'remove'])->name('favorites.remove');
    Route::get('/favorites/{id}/check', [FavoriteController::class, 'check'])->name('favorites.check');
});