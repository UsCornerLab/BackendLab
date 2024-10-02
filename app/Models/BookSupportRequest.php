<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookSupportRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_name',
        'contact_name',
        'email',
        'phone_number',
        'requested_book_titles',
        'number_of_books',
        'request_letter',
        'status',
        'admin_comments',
    ];

    protected $casts = [
        'requested_book_titles' => 'array',
    ];
}
