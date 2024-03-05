<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'document_type',
        'document_number',
        'name',
        'last_name',
        'email',
        'phone_number',
        'license_start',
        'license_end',
        'agency_id',
        'is_enabled',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }


    public $headers =  [
        ['text' => "ID", 'value' => "id"],
        ['text' => "Número de documento", 'value' => "document_number"],
        ['text' => "Nombre", 'value' => "name"],
        ['text' => "Apellidos", 'value' => "last_name"],
        ['text' => "Correo", 'value' => "email"],
        ['text' => "Teléfono", 'value' => "phone_number"],
        ['text' => "Inicio de licencia", 'value' => "license_start"],
        ['text' => "Fin de licencia", 'value' => "license_end"],
        ['text' => "Estado", 'value' => "is_enabled"],
    ];
}
