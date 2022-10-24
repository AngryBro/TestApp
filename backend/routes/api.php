<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDataController;
use App\Http\Controllers\StudentDataController;
use App\Http\Controllers\TeacherDataController;

Route::any('/debug','App\Http\Controllers\Debug@debug');

Route::post('/login','App\Http\Controllers\AuthController@login');


Route::middleware(App\Http\Middleware\User::class)
->group(function () {

    Route::middleware(App\Http\Middleware\Admin::class)
    ->group(function() {
        Route::post('/teacher.new',[AdminDataController::class,'teacherNew']);
        Route::post('/teacher.delete',[AdminDataController::class,'teacherDelete']);
        Route::get('/user.data',[AdminDataController::class, 'getUserData']);
        Route::post('/user.data.update',[AdminDataController::class, 'updateUserData']);

    });

    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
});
