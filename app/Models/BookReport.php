<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReport extends Model
{
    use HasFactory;

    public $table = "BookReport";

    protected $fillable = [
        "book_id"
    ];

    public function book() {
        return $this->belongsTo(User::class, "book_id");
    }
}
