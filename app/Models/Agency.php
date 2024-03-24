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
        ['text' => "ID", 'value' => "id"],
        ['text' => "Nombre", 'value' => "name"],
        ['text' => "Codigo", 'value' => "code_nsc"],
        ['text' => "RUC", 'value' => "ruc"],
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
    /*
             DB::raw("CONCAT(DATE_FORMAT(instructors.license_start, '%d/%m/%Y') , ' - ' ,DATE_FORMAT(instructors.license_end, '%d/%m/%Y')) as validity_period"),
                DB::raw("DATEDIFF(instructors.license_end, CURDATE()) as days_remaining"),
    */
    public function getValidityPeriodAttribute()
    {
        return $this->license_start . ' - ' . $this->license_end;
    }

    public function getDaysRemainingAttribute()
    {
        $end = Date::parse($this->license_end);
        $now = Date::now()->format('Y-m-d');
        //si la fecha de vencimiento es menor a la fecha actual se retorna en negativo
        if ($end->lt($now)) {
            return $end->diffInDays($now) * -1;
        }
        return $end->diffInDays($now);
    }
}
