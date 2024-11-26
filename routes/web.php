<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Гостевая часть (не требует авторизации)
|--------------------------------------------------------------------------
*/

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Страница "О нас"
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Страница "Контакты"
Route::get('/contacts', [HomeController::class, 'contacts'])->name('contacts');

// Карта всех профилей (Яндекс.Карты)
Route::get('/profiles/map', [HomeController::class, 'profilesMap'])->name('profiles.map');

// Список всех услуг
Route::get('/services', [HomeController::class, 'listServices'])->name('services.index');

// Все профили по категории (службе/атрибуту) — для списка labels
// Например: /services/massage
Route::get('/services/{slug}', [ProfileController::class, 'listByService'])
    ->name('services.profiles');

// Все профили по станции метро
// Например: /metro/teatralnaya
Route::get('/metro/{slug}', [ProfileController::class, 'listByMetro'])
    ->name('profiles.byMetro');

// Все профили по городу
// Например: /cities/moscow
Route::get('/cities/{slug}', [ProfileController::class, 'listByCity'])
    ->name('profiles.byCity');

// Все профили по району
// Например: /districts/arbat
Route::get('/districts/{slug}', [ProfileController::class, 'listByDistrict'])
    ->name('profiles.byDistrict');

// Страница расширенного поиска
Route::get('/search/advanced', [SearchController::class, 'advanced'])
    ->name('search.advanced');

Route::get('/profiles/{profile}-{slug}', [ProfileController::class, 'show'])
    ->name('profiles.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Авторизованная часть (требует middleware 'auth')
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Добавление профиля
    Route::get('/profiles/create', [ProfileController::class, 'create'])
        ->name('profiles.create');
    Route::post('/profiles', [ProfileController::class, 'store'])
        ->name('profiles.store');

    // Редактирование профиля
    Route::get('/profiles/{profile}/edit', [ProfileController::class, 'edit'])
        ->name('profiles.edit');
    Route::put('/profiles/{profile}', [ProfileController::class, 'update'])
        ->name('profiles.update');

    // Список всех профилей пользователя (пример URL: /my-profiles)
    // Можно сделать и /profiles (если это список только своих)
    Route::get('/my-profiles', [ProfileController::class, 'myList'])
        ->name('profiles.myList');
});


require __DIR__.'/auth.php';

Route::fallback(function () {
    abort(404);
});
