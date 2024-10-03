<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'scanned_at',
    ];
    
    public function student()
{
    return $this->belongsTo(Student::class);
}
}
