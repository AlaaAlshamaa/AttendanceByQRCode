<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use App\Models\Attendance;

class AttendanceExport implements FromArray, WithHeadings, WithStyles
{
    protected $students;
    protected $sessionDates;

    public function __construct($students, $sessionDates)
    {
        $this->students = $students;

        // Group attendance by distinct session dates (grouped by day)
        $this->sessionDates = Attendance::selectRaw('DATE(scanned_at) as day')
            ->distinct()
            ->pluck('day')
            ->toArray();
    }

    // The array method builds the data for the Excel file
    public function array(): array
    {
        $data = [];

        foreach ($this->students as $student) {
            $attendanceMap = [];
            // Check attendance for each session date grouped by day
            foreach ($this->sessionDates as $sessionDay) {
                // Check if the student attended on this day
                $attendedOnDay = $student->attendances
                    ->where('scanned_at', '>=', $sessionDay . ' 00:00:00')
                    ->where('scanned_at', '<=', $sessionDay . ' 23:59:59')
                    ->isNotEmpty();
                // Use 'م' for present and 'غ' for absent
                $attendanceMap[] = $attendedOnDay ? 'م' : 'غ'; 
            }

            $data[] = array_merge(
                [$student->id, $student->name],
                $attendanceMap
            );
        }

        return $data;
    }

    // The headings method defines the column headers
    public function headings(): array
    {
        return array_merge(['ID', 'Name'], $this->sessionDates);
    }

    // The styles method applies styling to specific cells or rows
    public function styles(Worksheet $sheet)
    {
        // Set header row styles (bold text, background color)
        $sheet->getStyle('A1:Z1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // Set the font color (white)
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'], // Green background color
            ],
        ]);

        // Loop through rows to apply conditional styling for attendance
        $highestRow = $sheet->getHighestRow();
        for ($i = 2; $i <= $highestRow; $i++) {
            foreach (range('C', $sheet->getHighestColumn()) as $col) {
                $cell = $col . $i;
                $cellValue = $sheet->getCell($cell)->getValue();

                // Apply conditional styling based on attendance ('م' for present, 'غ' for absent)
                if ($cellValue === 'م') {
                    $sheet->getStyle($cell)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'abf7b1', // Green for present
                            ],
                        ],
                    ]);
                } elseif ($cellValue === 'غ') {
                    $sheet->getStyle($cell)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'f69697', // Red for absent
                            ],
                        ],
                    ]);
                }
            }
        }

        return [];
    }
}
