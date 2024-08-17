<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Origin extends Model
{
    use HasFactory;

    public $table = 'Origin_from';

    protected $fillable = [
        'org_name',
        'type'
    ];

    public function books() {
        return $this->hasMany(Book::class, 'from', 'id');
    }

    public function given() {
        return $this->hasOne(GivenTo::class, 'borrowed_id');
    }
}
