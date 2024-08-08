<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GivenTo extends Model
{
    use HasFactory;

    protected $fillable = [
        "book_id",
        "borrowed_by",
    ];

    protected $casts = [
        "book_id"=> "integer",
        "borrowed_by"=> "integer",
    ];

    public function book() {
        return $this->belongsTo(Book::class);
    }

    public function borrowedBy() {
        return $this->belongsTo(Origin::class);
    }
}
