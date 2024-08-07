<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title', 
        'author', 
        'request_letter', 
        "status"
    ];

    protected $casts = [
        'user_id' => 'integer',
        
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
