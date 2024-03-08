<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;

use App\Http\Controllers\StudentCertificateController;
use App\Http\Controllers\StudentController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('index');
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
    Route::resource('agencies', AgencyController::class)->middleware(['can:a.agencies']);
    Route::patch('agencies/{id}/change-state',  [AgencyController::class, 'changeState'])->middleware(['can:a.agencies']);
    //cursos
    Route::resource('courses', CourseController::class)->middleware(['can:a.courses']);
    Route::patch('courses/{id}/change-state',  [CourseController::class, 'changeState'])->middleware(['can:a.courses']);
    //usuarios
    //administradores
    Route::resource('administrators', AdministratorController::class)->middleware(['can:a.administrators']);
    Route::patch('administrators/{id}/change-state',  [AdministratorController::class, 'changeState'])->middleware(['can:a.administrators']);
    //instructores
    Route::resource('instructors', InstructorController::class)->middleware(['can:a.instructors']);
    Route::patch('instructors/{id}/change-state',  [InstructorController::class, 'changeState'])->middleware(['can:a.instructors']);
    //certificados
    Route::resource('certificates', CertificateController::class)->middleware(['can:a.certificates']);
    Route::patch('certificates/{id}/change-state',  [CertificateController::class, 'changeState'])->middleware(['can:a.certificates']);
});

Route::middleware('auth')->name('s.')->prefix('s')->group(function () {
    //instructores -  cambiar los metodos de los controladores
    Route::get('instructors',  [InstructorController::class, 'indexAgency'])->name('instructors')->middleware(['can:s.instructors']);
    Route::post('instructors',  [InstructorController::class, 'store'])->name('instructors')->middleware(['can:s.instructors']);
    Route::put('instructors/{id}',  [InstructorController::class, 'update'])->name('instructors')->middleware(['can:s.instructors']);
    Route::delete('instructors/{id}',  [InstructorController::class, 'destroy'])->name('instructors')->middleware(['can:s.instructors']);
    Route::patch('instructors/{id}/change-state',  [InstructorController::class, 'changeState'])->name('instructors')->middleware(['can:s.instructors']);

    //students
    Route::resource('students', StudentController::class)->middleware(['can:s.students']);

    //certificates
    Route::get('certificates',  [CertificateController::class, 'indexAgency'])->name('certificates')->middleware(['can:s.certificates']);
    Route::post('certificates',  [CertificateController::class, 'storeAgency'])->name('certificates')->middleware(['can:s.certificates']);
});

Route::middleware('auth')->name('i.')->prefix('i')->group(function () {
    //studentCertificates
    Route::get('certificates',  [StudentCertificateController::class, 'index'])->name('certificates')->middleware(['can:i.certificates']);
    Route::put('certificates/{id}/change-state',  [StudentCertificateController::class, 'changeState'])->name('certificates')->middleware(['can:i.certificates']);
});
