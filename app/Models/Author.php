<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    public $table = "Authors";

    protected $fillable = ['author_name'];

    public function books() {
        return $this->belongsToMany(Book::class, 'Author_Book');
    }
}
