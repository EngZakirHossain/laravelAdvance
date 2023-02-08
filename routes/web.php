<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\ModuleController;
use App\Http\Controllers\Backend\PermissionController;

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
//Backend Route

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function(){
    //Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    //resource Routes
    Route::resource('/module', ModuleController::class);
    Route::resource('/permission', PermissionController::class);
    Route::resource('/role', RoleController::class);

});
