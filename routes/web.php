<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserExportController;

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

// Root URL points to User list
Route::get('/', [UserController::class, 'index'])->name('home');

// DataTable server-side route
Route::get('/users/data', [UserController::class, 'data'])->name('users.data');

// CRUD routes
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/users/export/excel', [UserExportController::class, 'exportExcel'])->name('users.export.excel');
Route::get('/users/export/pdf', [UserExportController::class, 'exportPdf'])->name('users.export.pdf');


// Optional: redirect old /users URL to root
Route::redirect('/users', '/');
