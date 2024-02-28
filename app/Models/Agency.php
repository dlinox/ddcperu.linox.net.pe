<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;
    /*

     Schema::create('agencies', function (Blueprint $table) {
            // id de la agencia
            $table->id();
            // nombre de la agencia
            $table->string('name');
            // codigo NSC de la agencia
            $table->string('code');
            // ruc de la agencia
            $table->string('ruc');
            // denominacion de la agencia
            $table->string('denomination');
            //correo institucional de la agencia
            $table->string('email');
            //numero de telefono de la agencia
            $table->string('phone');
            //fecha de inicio de la licencia 
            $table->date('license_start');
            //fecha de fin de la licencia
            $table->date('license_end');
            //status de la agencia
            $table->boolean('status');
            
            $table->timestamps();
        });
    */

    protected $fillable = [
        'name',
        'code',
        'ruc',
        'denomination',
        'email',
        'phone',
        'license_start',
        'license_end',
        'status'
    ];

    protected $casts = [
        'license_start' => 'date',
        'license_end' => 'date',
        'status' => 'boolean'
    ];


    public $headers =  [
        ['text' => "ID", 'value' => "id"],
        ['text' => "Nombre", 'value' => "name"],
        ['text' => "Codigo", 'value' => "code"],
        ['text' => "RUC", 'value' => "ruc"],
        ['text' => "Denominacion", 'value' => "denomination"],
        ['text' => "Correo", 'value' => "email"],
        ['text' => "Telefono", 'value' => "phone"],
        ['text' => "Inicio de Licencia", 'value' => "license_start"],
        ['text' => "Fin de Licencia", 'value' => "license_end"],
        ['text' => "Estado", 'value' => "status"],
    ];
}
