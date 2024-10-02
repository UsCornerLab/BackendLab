<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportedBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_request_id',
        'title',
        'author',
        'isbn',
        'publisher',
        'date_of_approval',
        'delivery_status',
        'number_of_books',
    ];
}
