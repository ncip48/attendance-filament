<?php

namespace App\Models;

use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cog\Contracts\Ban\Bannable as BannableContract;

class Employee extends Model implements BannableContract
{
    use HasFactory, Bannable;

    protected $fillable = [
        'nip',
        'nik',
        'name',
        'email',
        'phone_number',
        'address',
        'position_id',
        'photo',
        'password',
        'last_education',
        'gender'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
