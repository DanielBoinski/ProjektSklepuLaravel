<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

use App\Models\Product;


// Strona główna - widoczna dla wszystkich
Route::get('/', function () {
    // pobierz 3 LOSOWE produkty (jeśli są)
    $products = Product::inRandomOrder()->take(3)->get();

    return view('home', compact('products'));
})->name('home');



// Pełny asortyment - widoczny dla wszystkich (z wyszukiwarką + paginacją)
Route::get('/sklep', function (Request $request) {

    $query = Product::query();

    // jeśli podano frazę q w adresie (np. /sklep?q=buty)
    if ($request->filled('q')) {
        $query->where('name', 'like', '%' . $request->q . '%');
    }

    // paginacja po 5 produktów na stronę
    $products = $query->paginate(5);

    return view('shop', [
        'products' => $products,
        'search'   => $request->q,
    ]);
})->name('shop');


// Formularz logowania
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Obsługa logowania (POST)
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Formularz rejestracji
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
// Obsługa rejestracji (POST)
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Wylogowanie
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Grupa dla ADMINA
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard admina
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Użytkownicy (CRUD)
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Wszystkie zamówienia
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders');
});


// Grupa dla MODERATORA
Route::middleware(['auth', 'role:moderator'])->prefix('moderator')->name('moderator.')->group(function () {
    Route::get('/', function () {
        return view('moderator.dashboard');
    })->name('dashboard');
});


// Grupa dla KLIENTA
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/', function () {
        return view('client.dashboard');
    })->name('dashboard');

    // Historia zamówień klienta
    Route::get('/orders', [OrderController::class, 'clientIndex'])->name('orders');
});


// Produkty - zarządzanie (admin i moderator)
Route::middleware(['auth', 'role:admin,moderator'])
    ->prefix('products')
    ->name('products.')
    ->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });


// Koszyk - tylko klient
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/koszyk', [CartController::class, 'index'])->name('cart.index');
    Route::get('/koszyk/dodaj/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/koszyk/usun/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/koszyk/zamow', [CartController::class, 'checkout'])->name('cart.checkout');
});
