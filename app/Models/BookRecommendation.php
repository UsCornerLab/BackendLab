<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRecommendation extends Model
{
   use HasFactory;
   protected $fillable = [
      'user_id',
      'book_title',
      'author',
      'reason',
      'status',
      'publisher'
   ];
   protected $casts = [
      'user_id' => 'integer',
   ];
   /**
    * Get the user that owns the recommendation.
    */
   public function user()
   {
      return $this->belongsTo(User::class);
   }

}

