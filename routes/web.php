<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('index');
Route::get('about', [MainController::class, 'about'])->name('about');
Route::get('home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');
Route::get('home/edit', [App\Http\Controllers\HomeController::class, 'edit'])->name('home.edit');
Route::put('home', [App\Http\Controllers\HomeController::class, 'update'])->name('home.update');
Route::resource('user', HomeController::class);

Auth::routes(['verify' => true]);