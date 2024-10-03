<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/export-attendance', function () {
    return Excel::download(new AttendanceExport, 'student_attendance.xlsx');
});
