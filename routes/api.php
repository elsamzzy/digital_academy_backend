<?php

use App\Http\Controllers\CommentController\CommentController;
use App\Http\Controllers\PostController\PostController;
use App\Http\Controllers\SkillController\SkillController;
use App\Http\Controllers\UserController\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('users/')->controller(UserController::class)->name('users.')->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
});
Route::prefix('dashboard/')->middleware('auth:sanctum')->name('dashboard.')->group(function () {
    Route::prefix('user/')->controller(UserController::class)->name('user.')->group(function () {
        Route::get('profile', 'getUserProfile')->name('profile');
        Route::post('profile/update', 'editUserProfile');
    });

    Route::prefix('skill/')->controller(SkillController::class)->name('skill.')->group(function () {
        Route::get('all', 'all')->name('all');
        Route::post('add', 'add')->name('add');
        Route::post('edit/{id}', 'edit')->name('edit');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('post/')->controller(PostController::class)->name('post.')->group(function () {
        Route::get('all', 'all')->name('all');
        Route::post('add', 'add')->name('add');
        Route::post('edit/{id}', 'edit')->name('edit');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('comment/')->controller(CommentController::class)->name('comment.')->group(function () {
        Route::get('all/{post_id}', 'all')->name('all');
        Route::post('add', 'add')->name('add');
        Route::post('edit/{id}', 'edit')->name('edit');
        Route::delete('delete/{post_id}/{id}', 'delete')->name('delete');
    });
});
