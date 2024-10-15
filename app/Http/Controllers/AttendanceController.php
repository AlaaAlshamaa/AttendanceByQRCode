<?php

namespace App\Http\Controllers;

use App\Models\student;
use App\Models\Attendance;
use Illuminate\Http\Request;

use App\Exports\AttendanceExport;

use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function scanQRCode(Request $request)
    {
        $studentId = $request->query('id') ?? $request->getContent();
        $id = $request->json('id'); 

        // Find the user by their id
        $student = Student::where('id', $studentId)->first();

        if ($student) {
            // Create a new attendance record
            Attendance::create([
                'student_id' => $student->id,
                'scanned_at' => now(),
            ]);

           return response('<p style="font-size: 70px;"> Attendance recorded! </br>'.$student->name.'</br>'.$student->id.' </p>', 200)
           ->header('Content-Type', 'text/html');}

        return response()->json(['message' => 'Student not found!'], 404);
    }

    public function exportAttendance()
    {
        // Fetch all students
        $students = Student::all();

        // Fetch distinct session dates from the attendance records
        $sessionDates = Attendance::distinct()->pluck('scanned_at')->toArray();

        // Pass the $students and $sessionDates to the AttendanceExport class
        return Excel::download(new AttendanceExport($students, $sessionDates), 'attendance.xlsx');
    }
}
