<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlbumsController;
use App\Http\Controllers\PhotosController;

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

Route::get('/', function () {
    return view('index');
});
Route::get('/albums', [AlbumsController::class, 'index']);
Route::get('/albums/{id}', [AlbumsController::class, 'show']);
Route::get('/albums/{id}/add', [AlbumsController::class, 'addPhotoForm']);
Route::post('/albums/{id}/add', [AlbumsController::class, 'addPhoto']);
Route::post('/photos/{id}/delete', [AlbumsController::class, 'deletePhoto'])
    ->name('photos.delete');
Route::get('/photos', [PhotosController::class, 'index']);
Route::get('/photos/add', [PhotosController::class, 'addPhotoForm']);
Route::post('/photos/add', [PhotosController::class, 'addPhoto']);
