<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\student;
use Illuminate\Http\Request;


class StudentController extends Controller
{

     public function generateQrCode($id)
    {
    // Hardcode your computer's IP address and the desired URL
   $url = 'http://172.16.58.85:8000/api/scan?id=' . $id;
    // Generate and return the QR code containing the URL
    return QrCode::size(300)->generate($url);
    }

   
public function generateQrCodesForAllStudents()
    {
        // Fetch all students from the database
        $students = Student::all();
    
        // Create an array to store generated QR codes
        $qrCodes = [];
    
        // Loop through each student and generate QR codes
        foreach ($students as $student) {
            // Construct the URL with the student's ID (replace with your IP)
            $url = 'http://172.16.58.85:8000/api/scan?id=' . $student->id;
    
            // Generate the QR code (QR code is returned as HTML)
            $qrCode = QrCode::size(52 )  ->color(0, 0, 0) ->generate($url);
    
            // Add student name and QR code to the array
            $qrCodes[] = [
                'student_name' => $student->name,  // You can display the student's name
                'qr_code' => $qrCode
            ];
        }
        
        return view('qr_codes', compact('qrCodes'));
    
    }


    /**
     * Display a listing of the resource.
     */
 
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(student $student)
    {
        //
    }
}
