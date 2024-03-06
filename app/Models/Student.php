<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'document_number',
        'name',
        'paternal_surname',
        'maternal_surname',
        'email',
        'phone_number',
        'agency_id',
        'is_enabled'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public $headers = [
        ['text' => "ID", 'value' => "id"],
        ['text' => "Tipo de documento", 'value' => "document_type"],
        ['text' => "Número de documento", 'value' => "document_number"],
        ['text' => "Nombre", 'value' => "name"],
        ['text' => "Apellido paterno", 'value' => "paternal_surname"],
        ['text' => "Apellido materno", 'value' => "maternal_surname"],
        ['text' => "Correo", 'value' => "email"],
        ['text' => "Teléfono", 'value' => "phone_number"],
    ];
}
