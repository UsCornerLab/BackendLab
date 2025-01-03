<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public $table = 'Book';

    protected $fillable = [
        "title",
        'ISBN',
        'publisher',
        'publication_date',
        'cover_image_path',
        'accession_number',
        'category_id',
        'from',
        'status',
        'added_by',
        'copies',
        'available_copies'
    ];

    protected $casts = [
        'publication_date'=> 'date',
        'accession_number' => 'integer',
        'category_id' => 'integer',
        'from' => 'integer'

    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

     public function origin(){
        return $this->belongsTo(Origin::class, 'from');
    } 

    public function genres() {
        return $this->belongsToMany(Genre::class, 'Genre_Book');
    }

    public function added_by() {
        return $this->belongsTo(User::class, "added_by");
    }

    public function authors() {
        return $this->belongsToMany(Author::class, 'Author_Book');
    }

    public function bookReport() {
        return $this->hasOne(BookReport::class, "book_id");
    }

    public function borrow() {
        return $this->hasOne(Borrow::class);
    }

    public function shelf() {
        return $this->hasOne(Shelf::class, "book_id", "id");
    }

    public function givenTo() {
        return $this->hasOne(GivenTo::class);
    }

    public function checkStatus()//to check current status
    {
        return $this->status; 
    }
   
}
