<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TodoController;
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


Route::group([
    'prefix' => 'task',
], function () {
    
    Route::get('/', [TodoController::class, 'home']);
    Route::resource('list', TodoController::class);

    Route::post('create', [TodoController::class, 'store']);
    Route::post('update/{todo}', [TodoController::class, 'update']);
    Route::post('delete/{todo}', [TodoController::class, 'destroy']);
    // Route::get('list', [TodoController::class, 'index'])->name('list');
    
});
