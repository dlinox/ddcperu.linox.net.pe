<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorLicensed extends Model
{
    use HasFactory;
    /*
     Schema::create('instructor_licenseds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instructor_id');
            $table->unsignedBigInteger('course_id');
            $table->date('start_date');
            $table->date('end_date');

            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_current')->default(false);

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');
            $table->timestamps();
        });
    */

    protected $fillable = [
        'instructor_id',
        'course_id',
        'start_date',
        'end_date',
        'is_enabled',
        'is_current'
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeDisabled($query)
    {
        return $query->where('is_enabled', false);
    }

    public function scopeInstructor($query, $instructor_id)
    {
        return $query->where('instructor_id', $instructor_id);
    }

    public function scopeCourse($query, $course_id)
    {
        return $query->where('course_id', $course_id);
    }

    public $headers =  [
        ['text' => "Curso", 'value' => "course"],
        ['text' => "Fecha de inicio", 'value' => "start_date"],
        ['text' => "Fecha de fin", 'value' => "end_date"],
        //days_remaining
        ['text' => "Días restantes", 'value' => "days_remaining"],

    ];

    
}
