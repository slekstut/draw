<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilenameValidationController;
use App\Http\Controllers\DrawingController;

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
    return view('welcome');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

Route::post('/api/validate-filename', [FilenameValidationController::class, 'validateFilename']);
Route::post('/api/upload', [DrawingController::class, 'store']);
