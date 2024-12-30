<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function(){
    Route::post('register','register');
    Route::post('login','login');
    Route::get('user','userProfile')->middleware('auth:sanctum');
    Route::get('logout','userLogout')->middleware('auth:sanctum');

});
// API_END_POINT:
// Register:
// http://localhost:8000/api/register
// Login:
// http://localhost:8000/api/login
// User:
// http://localhost:8000/api/user
// Logout:
// http://localhost:8000/api/logout