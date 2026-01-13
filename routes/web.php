<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

Route::get('/', function () {
    return view('student.login');
})->name('home');

Route::get('/student/login', function () {
    return view('student.login');
})->name('student.login');

Route::post('/student/login', [StudentController::class, 'login'])->name('student.login.post');

Route::get('/student/register', function () {
    return view('student.register');
})->name('student.register');

Route::post('/student/register', [StudentController::class, 'register'])->name('student.register.post');

Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');

Route::get('/student/manage-book', [StudentController::class, 'manageBook'])->name('student.manage.book');

Route::get('/student/profile', [StudentController::class, 'profile'])->name('student.profile');
Route::post('/student/profile', [StudentController::class, 'updateProfile'])->name('student.profile.update');

Route::get('/student/photo/delete/{id}', [StudentController::class, 'deletePhoto'])->name('student.photo.delete');

Route::get('/student/books', [StudentController::class, 'manageBooks'])->name('student.books.manage');

Route::get('/student/challenges', [StudentController::class, 'manageChallenges'])->name('student.challenges.manage');

Route::get('/student/reports', [StudentController::class, 'reports'])->name('student.reports');
Route::get('/student/progress-dashboard', [StudentController::class, 'progressDashboard'])->name('student.progress.dashboard');

Route::post('/student/books', [StudentController::class, 'storeBook'])->name('student.books.store');
Route::put('/student/books/{id}', [StudentController::class, 'updateBook'])->name('student.books.update');
Route::delete('/student/books/{id}', [StudentController::class, 'deleteBook'])->name('student.books.delete');
Route::post('/student/summaries', [StudentController::class, 'storeSummary'])->name('student.summaries.store');
Route::put('/student/summaries/{id}', [StudentController::class, 'updateSummary'])->name('student.summaries.update');

Route::post('/student/challenges', [StudentController::class, 'storeChallenge'])->name('student.challenges.store');
Route::put('/student/challenges/{id}', [StudentController::class, 'updateChallenge'])->name('student.challenges.update');
Route::delete('/student/challenges/{id}', [StudentController::class, 'deleteChallenge'])->name('student.challenges.delete');

Route::post('/student/logout', [StudentController::class, 'logout'])->name('student.logout');

Route::get('/teacher/login', function () {
    return view('teacher.login');
})->name('teacher.login');

Route::post('/teacher/login', [TeacherController::class, 'login'])->name('teacher.login.post');

Route::get('/teacher/register', function () {
    return view('teacher.register');
})->name('teacher.register');

Route::post('/teacher/register', [TeacherController::class, 'register'])->name('teacher.register.post');

Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');

Route::get('/teacher/profile', [TeacherController::class, 'profile'])->name('teacher.profile');
Route::post('/teacher/profile', [TeacherController::class, 'updateProfile'])->name('teacher.profile.update');
Route::get('/teacher/photo/delete/{id}', [TeacherController::class, 'deletePhoto'])->name('teacher.photo.delete');

Route::get('/teacher/students', [TeacherController::class, 'students'])->name('teacher.students');
Route::get('/teacher/students/pdf', [TeacherController::class, 'studentsPdf'])->name('teacher.students.pdf');
Route::delete('/teacher/students/{id}', [TeacherController::class, 'deleteStudent'])->name('teacher.students.delete');

Route::get('/teacher/reports', [TeacherController::class, 'reports'])->name('teacher.reports');
Route::get('/teacher/progress-dashboard', [TeacherController::class, 'progressDashboard'])->name('teacher.progress.dashboard');
Route::put('/teacher/summaries/{id}/comment', [TeacherController::class, 'updateComment'])->name('teacher.summaries.comment');

Route::post('/teacher/logout', [TeacherController::class, 'logout'])->name('teacher.logout');
