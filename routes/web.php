<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Redirect::route('auth.index');
});

Route::name('auth.')->prefix('auth')->group(function () {
    Route::get('',  [AuthController::class, 'index'])->name('index')->middleware('guest');
    Route::get('login',  [AuthController::class, 'index'])->name('index')->middleware('guest');
    Route::post('sign-in',  [AuthController::class, 'signIn'])->name('sign-in')->middleware('guest');
    Route::post('sign-out',  [AuthController::class, 'signOut'])->name('sign-out')->middleware('auth');

    //change-password

    Route::post('change-password',  [AuthController::class, 'changePassword'])->name('change-password')->middleware('auth');

    //Rutas para el recupero de contraseña
    Route::get('/forgot-password',  [AuthController::class, 'forgotPassword'])->middleware('guest')->name('password.request');
    Route::post('/forgot-password',  [AuthController::class, 'sendPasswordResetLink'])->middleware('guest')->name('password.email');

    Route::get('/reset-password/{token}',  [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.reset');
    Route::post('/reset-password',  [AuthController::class, 'updatePassword'])->middleware('guest')->name('password.update');
});

Route::middleware('auth')->name('a.')->prefix('a')->group(function () {
    Route::get('',  [AdminController::class, 'index'])->name('index');

    //agencias
    Route::resource('agencies', AgencyController::class)->middleware(['can:a.users']);
    Route::patch('agencies/{id}/change-state',  [AgencyController::class, 'changeState'])->middleware(['can:a.users']);

    //cursos
    Route::resource('courses', CourseController::class)->middleware(['can:a.users']);
    Route::patch('courses/{id}/change-state',  [CourseController::class, 'changeState'])->middleware(['can:a.users']);

    //usuarios
    //administradores
    Route::resource('administrators', AdministratorController::class)->middleware(['can:a.users']);

    //operadores
    Route::resource('operators', OperatorController::class)->middleware(['can:a.users']);

    //instructores
    Route::resource('instructors', InstructorController::class)->middleware(['can:a.users']);
});
