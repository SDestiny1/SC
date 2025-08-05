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
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\BecaController;
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
Route::post('/importar-docentes', [TeacherController::class, 'import'])->name('teachers.import');

Route::resource('posts', PostController::class);
Route::patch('/posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])
    ->name('posts.toggle-status');
Route::get('/posts/{id}/details', [PostController::class, 'details'])->name('posts.details');


Route::resource('groups', GroupController::class);

Route::put('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');
Route::post('/calendario', [CalendarioController::class, 'store'])->name('calendario.store');
Route::get('/plantilla-calendario', function () {
    return response()->download(storage_path('app/public/plantillas/plantilla_calendario.csv'));
})->name('plantilla.calendario');
Route::post('/calendario/importar', [CalendarioController::class, 'importar'])->name('calendario.importar');
Route::delete('/calendario/{year}/{eventIndex}', [CalendarioController::class, 'destroy']);

Route::prefix('noticias')->group(function () {
    Route::get('/', [NoticiaController::class, 'index'])->name('noticias.index');
    Route::get('/crear', [NoticiaController::class, 'create'])->name('noticias.create');
    Route::post('/', [NoticiaController::class, 'store'])->name('noticias.store');
    Route::get('/{id}/editar', [NoticiaController::class, 'edit'])->name('noticias.edit');
    Route::put('/{id}', [NoticiaController::class, 'update'])->name('noticias.update');
    Route::patch('/{id}/toggle-status', [NoticiaController::class, 'toggleStatus'])->name('noticias.toggle-status');
    Route::delete('/{id}', [NoticiaController::class, 'destroy'])->name('noticias.destroy');
});

// En routes/web.php
Route::post('/students/import', [StudentController::class, 'import'])
    ->name('students.import')
    ->middleware('auth'); // Asegúrate de que el usuario esté autenticado

Route::get('/becas', [BecaController::class, 'index'])->name('becas.index');
Route::get('/becas/create', [BecaController::class, 'create'])->name('becas.create');
Route::post('/becas', [BecaController::class, 'store'])->name('becas.store');
Route::get('/becas/{id}', [BecaController::class, 'show'])->name('becas.show');

Route::get('/teachers/{teacher}/schedule/create', [TeacherController::class, 'createSchedule'])
    ->name('teachers.schedule.create')
    ->middleware('can:edit teachers');
Route::post('/teachers/{teacher}/assign-schedule', [TeacherController::class, 'assignSchedule'])->name('teachers.assignSchedule');
Route::post('/teachers/assign-schedule', [TeacherController::class, 'assignSchedule'])->name('teachers.assignSchedule');