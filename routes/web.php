<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

use App\Models\Product;



Route::get('/', function () {
   
    $products = Product::inRandomOrder()->take(3)->get();

    return view('home', compact('products'));
})->name('home');



Route::get('/sklep', function (Request $request) {

    $query = Product::query();


    if ($request->filled('q')) {
        $query->where('name', 'like', '%' . $request->q . '%');
    }


    $products = $query->paginate(5);

    return view('shop', [
        'products' => $products,
        'search'   => $request->q,
    ]);
})->name('shop');



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');


Route::post('/login', [AuthController::class, 'login'])->name('login.post');


Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {


    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');


    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');


    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders');
});



Route::middleware(['auth', 'role:moderator'])->prefix('moderator')->name('moderator.')->group(function () {
    Route::get('/', function () {
        return view('moderator.dashboard');
    })->name('dashboard');
});



Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/', function () {
        return view('client.dashboard');
    })->name('dashboard');


    Route::get('/orders', [OrderController::class, 'clientIndex'])->name('orders');
});



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



Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/koszyk', [CartController::class, 'index'])->name('cart.index');
    Route::get('/koszyk/dodaj/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/koszyk/usun/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/koszyk/zamow', [CartController::class, 'checkout'])->name('cart.checkout');
});
