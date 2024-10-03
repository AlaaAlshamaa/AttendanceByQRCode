<?php
namespace App\Exports;

use App\Models\Student;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping  
{
    // Store unique session dates grouped by day
    protected $sessionDates;

    public function __construct()
    {
        // Group attendance by distinct session dates (grouped by day)
        $this->sessionDates = Attendance::selectRaw('DATE(scanned_at) as day')
            ->distinct()
            ->pluck('day')
            ->toArray();
    }

    public function collection()
    {
        // Get all students with their attendance records
        return Student::with('attendances')->get();
    }

    public function headings(): array
    {
        // Merge headers for the student ID, name, session dates, and total attendance
        return array_merge(['id', 'name'], $this->sessionDates, ['Total Days Attended']);
    }

    public function map($student): array
    {
        // Array to hold the student data
        $attendanceMap = [];

        // Add student ID and name as the first columns
        $attendanceMap[] = $student->id;
        $attendanceMap[] = $student->name;

        // Variable to track total attendance days
        $totalDaysAttended = 0;

        // Check attendance for each session date grouped by day
        foreach ($this->sessionDates as $sessionDay) {
            // Check if the student attended on this day
            $attendedOnDay = $student->attendances
                ->where('scanned_at', '>=', $sessionDay . ' 00:00:00')
                ->where('scanned_at', '<=', $sessionDay . ' 23:59:59')
                ->isNotEmpty();

            // If attended, add 1; otherwise, add 0
            $attendanceMap[] = $attendedOnDay ? 'م' : 'غ';

            // Increment the total days attended count
            if ($attendedOnDay) {
                $totalDaysAttended++;
            }
        }

        // Add the total days attended at the end of the row
        $attendanceMap[] = $totalDaysAttended;

        return $attendanceMap;
    }
    
}
