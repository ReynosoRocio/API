<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable implements JWTSubject
{
    // Nombre de la tabla (opcional si sigue la convención de Laravel)
    protected $table = 'users';

    // Campos asignables masivamente
    protected $fillable = [
        'name',
        'lastname', // Updated field
        'dateBirth',
        'userType', // Updated field
        'stateBirth', // Updated field
        'email',
        'password',
        'new_password',
        'current_password',
    ];

    // Campos ocultos (como la contraseña)
    protected $hidden = [
        'password',
    ];

    // Métodos requeridos por JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getEncryptedIdAttribute()
    {
        return Crypt::encryptString('users_' . $this->attributes['id']);
    }

    // Método para desencriptar el ID
    public static function decryptId($encryptedId)
    {
        $decrypted = Crypt::decryptString($encryptedId);
        return str_replace('users_', '', $decrypted);
    }
}