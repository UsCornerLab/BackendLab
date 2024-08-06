<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        'ISBN',
        'publisher',
        'publication_date',
        'category_id',
        'cover_image_path',
        'accession_number'
    ];

    protected $casts = [
        'publication_date'=> 'datetime',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function genres() {
        return $this->belongsToMany(Genre::class);
    }

    public function authors() {
        return $this->belongsToMany(Author::class);
    }
}
