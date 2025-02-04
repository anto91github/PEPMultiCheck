<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PEPCheckController;

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
    // return view('welcome');
    return redirect()->route('dashboard');
});

Route::get('/about', function () {
    return view('about');
});

//Route::post('/register', 'RegisterController@create')->name('register');
Route::post('/register', [RegisterController::class, 'create'])->name('register');


/**
 * Auth Routes
 */
Auth::routes(['verify' => false]);


Route::group(['namespace' => 'App\Http\Controllers'], function()
{   
    Route::middleware('auth')->group(function () {
        /**
         * Home Routes
         */
        Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
        /**
         * Role Routes
         */    
        Route::resource('roles', RolesController::class);
        /**
         * Permission Routes
         */    
        Route::resource('permissions', PermissionsController::class);
        /**
         * User Routes
         */
        
        Route::group(['prefix' => 'users'], function() {
            Route::get('/', [App\Http\Controllers\UsersController::class, 'index'])->name('users.index');
            Route::get('/create', 'UsersController@create')->name('users.create');
            Route::post('/create', 'UsersController@store')->name('users.store');
            Route::get('/{user}/show', 'UsersController@show')->name('users.show');
            Route::get('/{user}/edit', 'UsersController@edit')->name('users.edit');
            Route::patch('/{user}/update', 'UsersController@update')->name('users.update');
            Route::delete('/{user}/delete', 'UsersController@destroy')->name('users.destroy');
        });

        Route::group(['prefix' => 'pepCheck'], function() {
            Route::get('/', [App\Http\Controllers\PEPCheckController::class, 'index'])->name('pepCheck.index');
            Route::get('/create', 'PEPCheckController@create')->name('pepCheck.create');
            Route::get('/detail/{id}', [PEPCheckController::class, 'show']);
            Route::get('/delete/{id}', [PEPCheckController::class, 'delete']);
            
            Route::post('/upload', 'PEPCheckController@upload')->name('pepCheck.upload');            
            Route::get('/download', [PEPCheckController::class, 'download'])->name('pepCheck.download');
        });

    });
});
