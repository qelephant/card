<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MethodController;
use App\Http\Controllers\StrategyController;
use App\Models\Lesson;
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

Route::match(['post', 'get'], '/card/{card}/lesson/create', [LessonController::class, 'create'])->name('lesson.create');
Route::resource('/card/{card}/lesson', LessonController::class)->except('create');

//Route::post('/card/{card}/lesson/precreate', [LessonController::class, 'preCreate'])->name('lesson.pre.create');

Route::resource('strategies', StrategyController::class);
Route::resource('methods', MethodController::class);

//Route::get('card/{card}/lesson/', [LessonController::class, 'create'])->name('lesson.postcreate');
//Route::resource('card', CardController::class);
Route::get('card/{id}', [CardController::class, 'index'])->name('card.index');
Route::get('lesson/{id}/generate', [LessonController::class, 'generateDocument'])->name('lesson.generate');
Route::post('lesson/upload', [LessonController::class, 'upload'])->name('lesson.upload');
//Route::delete('card/{card}/lesson/{id}', [LessonController::class, 'desctroy'])->name('lesson.destroy');
