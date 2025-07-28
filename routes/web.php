<?php

use App\Http\Controllers\TextRowController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [TextRowController::class, 'index'])->name('home');
Route::get('/create', [TextRowController::class, 'create'])->name('create');
Route::post('/store', [TextRowController::class, 'store'])->name('store');
Route::get('/edit/{textRow}', [TextRowController::class, 'edit'])->name('edit');
Route::patch('/update/{textRow}', [TextRowController::class, 'update'])->name('update');
Route::delete('/delete/{textRow}', [TextRowController::class, 'delete'])->name('delete');
Route::delete('/delete-all', [TextRowController::class, 'deleteAll'])->name('delete-all');
Route::post('/generate', [TextRowController::class, 'generate'])->name('generate');
