<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wasaya_session extends Model
{
    use HasFactory;

    protected $fillable = [
        'on_date',
        'title',
        'note'
    ];
}
