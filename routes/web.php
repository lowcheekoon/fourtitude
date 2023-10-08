<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/product', [App\Http\Controllers\ProductController::class, 'index'])->name('product.index');
    Route::get('/product/get-list', [App\Http\Controllers\ProductController::class, 'getList'])->name('product.getList');
    Route::get('/product/{code}', [App\Http\Controllers\ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/{code}', [App\Http\Controllers\ProductController::class, 'update'])->name('product.update');
    Route::post('/product/create', [App\Http\Controllers\ProductController::class, 'store'])->name('product.store');
    Route::delete('/product/{code}', [App\Http\Controllers\ProductController::class, 'delete'])->name('product.delete');
});

require __DIR__.'/auth.php';
