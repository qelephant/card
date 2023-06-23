<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MethodController;
use App\Http\Controllers\StrategyController;
use App\Models\Strategy;
use Illuminate\Support\Facades\Route;

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
    $strategies = Strategy::with('cards')->get();
    return view('welcome', compact('strategies'));
});
Route::resource('/card/{card}/lesson', LessonController::class);
Route::resource('strategies', StrategyController::class);
Route::resource('methods', MethodController::class);

//Route::get('card/{id}/lesson/', [LessonController::class, 'create'])->name('lesson.create');
//Route::resource('card', CardController::class);
Route::get('card/{id}', [CardController::class, 'index'])->name('card.index');
