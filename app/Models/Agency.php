<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Agency extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'code_nsc',
        'ruc',
        'denomination',
        'email_institutional',
        'phone',
        'license_start',
        'license_end',
        'is_enabled'
    ];

    protected $casts = [

        'is_enabled' => 'boolean'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'validity_period',
        'days_remaining'
    ];


    public $headers =  [
        ['text' => "Codigo NSC", 'value' => "code_nsc"],
        ['text' => "RUC", 'value' => "ruc"],
        ['text' => "Nombre", 'value' => "name"],
        ['text' => "Denominacion", 'value' => "denomination"],
        ['text' => "Correo", 'value' => "email_institutional"],
        ['text' => "Telefono", 'value' => "phone"],
        ['text' => "Periodo de validacion", 'value' => "validity_period"],
        ['text' => "Vencimiento de licencia", 'value' => "days_remaining"],
        ['text' => "Estado", 'value' => "is_enabled"],
    ];

    //users
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function getValidityPeriodAttribute()
    {
        return Date::parse($this->license_start)->format('Y/m/d') . '-' . Date::parse($this->license_end)->format('Y/m/d');
    }

    public function getDaysRemainingAttribute()
    {
        $end = Date::parse($this->license_end);
        $now = Date::now()->format('Y-m-d');
        if ($end->lt($now)) {
            return $end->diffInDays($now) * -1;
        }
        return $end->diffInDays($now);
    }
}
