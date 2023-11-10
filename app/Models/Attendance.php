<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'clock_in',
        'clock_out',
        'clock_in_location',
        'clock_out_location',
        'clock_in_note',
        'clock_out_note',
        'clock_in_image',
        'clock_out_image',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
