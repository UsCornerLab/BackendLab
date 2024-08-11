<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;

    protected $fillable = [
        'shelf_name',
        'shelf_number',
        'book_id'
    ];

    protected $casts = [
        'shelf_number' => 'integer',
        'book_id' => 'integer'

    ];

    public function books() {
        return $this->belongsTo(Book::class);
    }
}
