<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;


    protected $fillable = [
        'agency_id',
        'course_id',
        'user_id',
        'is_enabled'
    ];

    protected $casts = [
        'is_enabled' => 'boolean'
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
        ['text' => "ID", 'value' => "id"],
        ['text' => "Sub agencia", 'value' => "agency"],
        ['text' => "Curso", 'value' => "course"],
        ['text' => "Usuario", 'value' => "user"],
        ['text' => "Estado", 'value' => "is_enabled"],
    ];

    public $headersAgency =  [
        // ['text' => "ID", 'value' => "id"],
        ['text' => "Curso", 'value' => "course"],
    ];
}
