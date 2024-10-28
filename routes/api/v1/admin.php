<?php

use App\Http\Controllers\Api\Admin\V1\Auth\AuthController;
use App\Http\Controllers\Api\Admin\V1\CategoryManagement\CategoryController;
use App\Http\Controllers\Api\Admin\V1\UserManagement\UserController;
use App\Http\Controllers\Api\Admin\V1\StoryManagement\StoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/admin')->group(function () {
    Route::controller(AuthController::class)
        ->prefix('auth')
        ->name('admin.auth.')
        ->group(function () {
            Route::post('/login', 'login')->name('login');
            Route::post('/register', 'register')->name('register');
            Route::delete('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
        });

    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(CategoryController::class)
            ->prefix('categories')
            ->name('categories.')
            ->group(function () {
                Route::get('', 'index')->name('index');
                Route::post('', 'store')->name('store');
                Route::get('/{category}', 'show')->name('show');
                Route::put('/{category}', 'update')->name('update');
                Route::delete('/{category}', 'destroy')->name('destroy');
            });

        Route::controller(UserController::class)
            ->prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('', 'index')->name('index'); 
                Route::get('/{user}', 'show')->name('show'); 
                Route::delete('/{user}', 'destroy')->name('destroy');
            });
        
        Route::controller(StoryController::class)
            ->prefix('stories')
            ->name('stories.')
            ->group(function () {
                Route::get('', 'index')->name('index');
                Route::get('/{story}', 'show')->name('show');
                Route::delete('/{story}', 'destroy')->name('destroy');
            });
    });
});
