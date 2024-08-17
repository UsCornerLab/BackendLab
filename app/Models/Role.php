<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public $table = "Role";

    protected $fillable = ['role_type'];

    public $timestamps = false;

    public function users() {
        return $this->hasMany(User::class);
    }
}
