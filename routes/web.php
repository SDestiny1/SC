<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarioController;
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

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('students', controller: StudentController::class);

Route::resource('teachers', controller: TeacherController::class);

Route::resource('posts', PostController::class);
Route::patch('/posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])
    ->name('posts.toggle-status');

Route::resource('groups', GroupController::class);

Route::put('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');
Route::post('/calendario', [CalendarioController::class, 'store'])->name('calendario.store');
Route::get('/plantilla-calendario', function () {
    return response()->download(storage_path('app/public/plantillas/plantilla_calendario.csv'));
})->name('plantilla.calendario');
Route::post('/calendario/importar', [CalendarioController::class, 'importar'])->name('calendario.importar');

Route::get('/noticias', [PostController::class, 'mostrarNoticias'])->name('noticias.index');
