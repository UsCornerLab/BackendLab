<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $table = "User";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'birthDate',
        'role_id',
        'address',
        'id_photo_path',
        'profile',
        'verified'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

     /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'verified' => 'boolean',
            'password' => 'hashed',
            'role_id' => 'integer',
        ];
    }

    public function role() {
        return $this->belongsTo(Role::class, "role_id");
    }

    public function books() {
        return $this->hasMany(Book::class, 'added_by');
    }

    public function bookRequest() {
        return $this->hasOne(BookSupportRequest::class);
    }

    public function borrow() {
        return $this->hasMany(Borrow::class);
    }
    public function bookRecommendation()
    {
        return $this->hasMany(BookRecommendation::class);
    }
}
