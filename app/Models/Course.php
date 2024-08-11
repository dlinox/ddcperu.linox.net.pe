<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /*
$table->string('name');
            $table->string('code');
            $table->string('description')->nullable();
            $table->boolean('status')->default(true);
    */

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_enabled'
    ];

    protected $casts = [
        'is_enabled' => 'boolean'
    ];

    public $headers =  [
        ['text' => "Código", 'value' => "code"],
        ['text' => "Nombre", 'value' => "name"],
        ['text' => "Descripción", 'value' => "description"],
        ['text' => "Estado", 'value' => "is_enabled"],
    ];


    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }
    public function scopeForSelect($query)
    {
        return $query->select('id', 'name');
    }

    public function studentCertificates()
    {
        return $this->hasMany(StudentCertificate::class);
    }
}
