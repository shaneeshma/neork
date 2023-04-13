<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDetailsController;

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

Route::get('/', [UserDetailsController::class, 'index']);
Route::get('list_data', [UserDetailsController::class, 'list_data']);
Route::get('data_view', [UserDetailsController::class, 'data_view']);
Route::get('delete_user', [UserDetailsController::class, 'delete_user']);
Route::post('save', [UserDetailsController::class, 'save']);