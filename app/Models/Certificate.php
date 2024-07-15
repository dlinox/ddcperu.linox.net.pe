<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;


    protected $fillable = [
        'agency_id',
        'range_start',
        'range_end',
        'quantity',
        'user_id',
        'is_enabled'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'created_at' => 'datetime:d/m/Y',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function certificateDetails()
    {
        return $this->hasMany(CertificateDetail::class);
    }

    public $headers =  [
        ['text' => "Codigo NSC", 'value' => "code_nsc"],
        ['text' => "RUC", 'value' => "ruc"],
        ['text' => "Nombre", 'value' => "name"],
        ['text' => "Periodo de validacion", 'value' => "validity_period"],
        ['text' => "Vencimiento de licencia", 'value' => "days_remaining"],
    ];

    public $headersCertificates =  [

        ['text' => "Rango Inicial", 'value' => "range_start"],
        ['text' => "Rango Final", 'value' => "range_end"],
        ['text' => "Cantidad", 'value' => "quantity"],
        ['text' => "Fecha de creacion", 'value' => "created_at"],
    ];

    public $headersAgency =  [

        ['text' => "Numero", 'value' => "number"],
        ['text' => "Curso", 'value' => "course"],
        ['text' => "Estudiante", 'value' => "student"],
        ['text' => "Instructor", 'value' => "instructor"],
        ['text' => "Fecha de inicio", 'value' => "start_date"],
        ['text' => "Fecha de fin", 'value' => "end_date"],
        // ['text' => "Estado", 'value' => "status"],
        ['text' => "Aprobado", 'value' => "is_approved"],
        ['text' => "Fecha de creacion", 'value' => "created_at"],
        // ['text' => "Fecha de actualizacion", 'value' => "updated_at"],
    ];

    public $headersInstructor = [

        ['text' => "Numero", 'value' => "number"],
        ['text' => "Curso", 'value' => "course"],
        ['text' => "Estudiante", 'value' => "student"],
        ['text' => "Sub agencia", 'value' => "agency"],
        ['text' => "Fecha de inicio", 'value' => "start_date"],
        ['text' => "Fecha de fin", 'value' => "end_date"],
        // ['text' => "Estado", 'value' => "status"],
        ['text' => "Aprobado", 'value' => "is_approved"],
        ['text' => "Fecha de creacion", 'value' => "created_at"],
        // ['text' => "Fecha de actualizacion", 'value' => "updated_at"],
    ];
}
