<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCertificate extends Model
{
    use HasFactory;
    /*
        $table->id();
            //id del estudiante
            $table->foreignId('student_id')->constrained('students');
            //id del certificado
            $table->foreignId('certificate_id')->constrained('certificate_details');
            //usuario que registro el certificado
            $table->foreignId('user_id')->constrained('users');
            //estado de aprobacion
            $table->boolean('is_approved')->default(false);
            //estado registro
            $table->boolean('is_enabled')->default(true);
    */

    protected $fillable = [
        'student_id',
        'certificate_id',
        'user_id',
        'start_date',
        'end_date',
        'instructor_id',
        'is_approved',
        'is_enabled'
    ];

    protected $casts = [
        'is_approved' => 'integer', //0 - Pendiente, 1 - Aprobado, 2 - Rechazado
        'is_enabled' => 'boolean'
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

        ['text' => "Fecha de fin", 'value' => "end_date"],
        ['text' => "Estado", 'value' => "is_approved"],
    ];
    
}
