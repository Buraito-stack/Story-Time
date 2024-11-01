<?php

use App\Http\Controllers\Api\User\V1\Me\StoryManagement\StoryController;
use App\Http\Controllers\Api\User\V1\Auth\AuthController;
use App\Http\Controllers\Api\User\V1\Stories\PublicStoryController;
use App\Http\Controllers\Api\User\V1\Me\Profile\ProfileController;
use App\Http\Controllers\Api\User\V1\Me\Bookmark\BookmarkController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/user')->group(function () {
    Route::controller(AuthController::class)
        ->prefix('auth')
        ->name('user.auth.')
        ->group(function () {
            Route::post('/login', 'login')->name('login');
            Route::post('/register', 'register')->name('register');
            Route::delete('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
        });

    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(PublicStoryController::class)
            ->prefix('stories')
            ->name('stories.public')
            ->group(function () {
                Route::get('/', 'index')->name('index'); 
                Route::get('/{story}', 'show')->name('show'); 
            });

        Route::prefix('me')
            ->name('me.')
            ->group(function () {
            Route::controller(ProfileController::class)
                ->prefix('profile')
                ->name('profile.')
                ->group(function () {
                    Route::get('/', 'show')->name('show');
                    Route::post('/', 'update')->name('update');
                });

            Route::controller(BookmarkController::class)
                ->prefix('bookmarks')
                ->name('bookmarks.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/{story}', 'bookmark')->name('bookmark');
                    Route::delete('/{story}', 'destroy')->name('destroy');
                });

            Route::controller(StoryController::class)
                ->prefix('stories')
                ->name('stories.management.')
                ->group(function () {
                    Route::get('', 'index')->name('my.index');
                    Route::get('/{story}', 'show')->name('show');
                    Route::post('', 'store')->name('store');
                    Route::post('/{story}', 'update')->name('update');
                    Route::delete('/{story}', 'destroy')->name('destroy');
                });
        });
    });
});
