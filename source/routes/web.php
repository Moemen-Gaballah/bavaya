<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyController;

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

Auth::routes();



Route::group(['middleware' => 'auth'], function()
{
    Route::get('/home', [HomeController::class, 'index'])->name('home');
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
//    Route::put('/profile', [ProfileController::class, 'update'])->name('profile');


    Route::resource('tickets', TicketController::class);
    Route::get('tickets-datatable', [TicketController::class, 'datatable'])->name('tickets.datatable');

    Route::post('tickets/{id}/replies/store', [ReplyController::class, 'store'])->name('replies.store');

});
