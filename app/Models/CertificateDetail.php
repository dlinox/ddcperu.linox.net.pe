<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateDetail extends Model
{
    use HasFactory;

    // $table->id();
    // $table->foreignId('certificate_id')->constrained('certificates');
    // $table->char('number', 20);
    // $table->char('status', 3)->default('000');
    // $table->timestamps();
    // * 000: disponible
    // * 001: pendiente
    // * 002: asignado
    // * 003: vencido

    protected $fillable = [
        'certificate_id',
        'number',
        'course_id',
        'status'
    ];

    //actualizar el estado del certificado
    public function updateStatus($status)
    {
        
        $this->status = $status;
        $this->save();
    }
}
