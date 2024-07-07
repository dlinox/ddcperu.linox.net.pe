<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_id',
        'number',
        'status'
    ];


    protected $casts = [
        'updated_at' => 'datetime:d/m/Y',
    ];


    //actualizar el estado del certificado
    public function updateStatus($status)
    {
        
        $this->status = $status;
        $this->save();
    }
}
