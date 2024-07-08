<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedCertificate extends Model
{
    use HasFactory;
    /*
           $table->id();
            $table->integer('number');
            $table->unsignedBigInteger('instructor_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->string('reason')->nullable();
            $table->timestamps();
    */

    protected $fillable = [
        'number',
        'instructor_id',
        'student_id',
        'course_id',
        'reason'
    ];

    
}
