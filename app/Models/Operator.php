<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'document_number',
        'name',
        'last_name',
        'phone_number',
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
        ['text' => "Teléfono", 'value' => "phone_number"],
        ['text' => "Estado", 'value' => "is_enabled"],
    ];

    
}
