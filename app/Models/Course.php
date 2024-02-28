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
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public $headers =  [
        ['text' => "ID", 'value' => "id"],
        ['text' => "Nombre", 'value' => "name"],
        ['text' => "Código", 'value' => "code"],
        ['text' => "Descripción", 'value' => "description"],
        ['text' => "Estado", 'value' => "status"],
    ];
}
