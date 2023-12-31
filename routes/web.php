<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/fixture', [HomeController::class, 'fixture'])->name('fixture');
Route::get('/simulation', [HomeController::class, 'simulation'])->name('simulation');

Route::get('/simulate-all-matches', [MatchController::class, 'simulateAllMatches']);
Route::get('/simulate-match', [MatchController::class, 'simulateMatch']);
