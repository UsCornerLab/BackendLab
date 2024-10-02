<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;
    public $table = 'Borrow';
    protected $fillable = [
        'user_id',
        'copy_id',
        'status'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'copy_id'=> 'integer',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function book() {
        return $this->belongsTo(Book::class);
    }
}
