<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'paternal_surname',
        'maternal_surname',
        'phone_number',
        'document_number', // dni
        'username', // email
        'role',
        'agency_id',
        'email',
        'password',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'full_name',
    ];

    protected $with = [
        'permissions:id,name,menu'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->paternal_surname} {$this->maternal_surname}";
    }

    public $headers =  [
        ['text' => "ID", 'value' => "id"],
        ['text' => "Nombre", 'value' => "full_name"],
        ['text' => "Teléfono", 'value' => "phone_number"],
        ['text' => "Nombre de Usuario", 'value' => "username"],
        ['text' => "Rol", 'value' => "role"],

        ['text' => "Correo Electrónico", 'value' => "email"],
        ['text' => "Activo", 'value' => "is_active"],
        // ['text' => "Acciones", 'value' => "actions", 'sortable' => false],
    ];
}
