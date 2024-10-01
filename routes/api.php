<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;

Route::get('/student/{id}/qrcode', [StudentController::class, 'generateQRCode']);
Route::get('/student/qrcodes', [StudentController::class, 'generateQrCodesForAllStudents']);
Route::get('/scan', [AttendanceController::class, 'scanQRCode']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
