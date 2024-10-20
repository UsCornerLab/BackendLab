<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    public $table = 'Attendance';
    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'status'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'date' => 'date',
        'time_in' => 'time',
        'time_out' => 'time',
        'status' => 'string'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}  