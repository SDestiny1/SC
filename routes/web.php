<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GroupController;

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
    return view('landing');
});



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::resource('students', controller: StudentController::class);


Route::resource('teachers', TeacherController::class);


Route::resource('posts', PostController::class)->only(['index', 'destroy']);


Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
Route::resource('grups', GroupController::class);

// Ruta para configuración
Route::get('/settings', function () {
    return view('settings');
})->name('settings');
Route::put('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');



Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
