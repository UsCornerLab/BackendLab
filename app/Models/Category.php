<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $table = "Category";
    protected $fillable = ['category_name'];

    public function books() {
        return $this->hasMany(Book::class);
    }
}
