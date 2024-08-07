<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'certificate_id', // numero del certificado (Certicifate Details)
        'user_id',
        'start_date',
        'end_date',
        'instructor_id',
        'course_id',
        'is_approved',
        'is_enabled'
    ];

    protected $casts = [
        'is_approved' => 'integer', //0 - Pendiente, 1 - Aprobado, 2 - Rechazado
        'is_enabled' => 'boolean',
        'updated_at' => 'datetime:d/m/Y',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function certificate()
    {
        return $this->belongsTo(CertificateDetail::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    /*
    is_approved
    0 - Pendiente
    1 - Aprobado
    2 - Rechazado
    */

    public $headers = [
        ['text' => "ID", 'value' => "id"],
        ['text' => "Estudiante", 'value' => "student"],
        //curso
        ['text' => "Curso", 'value' => "course"],
        ['text' => "Certificado", 'value' => "certificate"],
        ['text' => "Fecha de inicio", 'value' => "start_date"],
        //agency
        ['text' => "Sub agencia", 'value' => "agency"],

        ['text' => "Fecha de fin", 'value' => "end_date"],
        ['text' => "Estado", 'value' => "is_approved"],
    ];
    
}
