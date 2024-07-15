<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'document_type',
        'document_number',
        'name',
        'last_name',
        'email',
        'phone_number',
        'agency_id',
        'is_enabled',
        'instructor_link',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }


    public $headers =  [
        ['text' => "ID", 'value' => "id"],
        ['text' => "Codigo", 'value' => "instructor_id"],
        ['text' => "Número de documento", 'value' => "document_number"],
        ['text' => "Nombre", 'value' => "full_name"],
        //dias restantes
        ['text' => "Correo", 'value' => "email"],
        ['text' => "Teléfono", 'value' => "phone_number"],
        // ['text' => "Periodo de validacion", 'value' => "validity_period"],
        // ['text' => "Vencimiento de licencia", 'value' => "days_remaining"],
        ['text' => "Sub agencia", 'value' => "agency"],
        ['text' => "Estado", 'value' => "is_enabled"],
    ];

    public static function instructorActiveCourses()
    {

        $instructors = Instructor::select(
            'instructors.id as value',
            DB::raw('CONCAT(instructors.name, " ", instructors.last_name) as title'),
            'instructor_licenseds.course_id'
        )
            ->join('instructor_licenseds', 'instructor_licenseds.instructor_id', '=', 'instructors.id')
            //que la fecha de inicio sea menor o igual a la fecha actual
            ->where('instructor_licenseds.start_date', '<=', DB::raw('CURDATE()'))
            //que los dias restantes sean mayores a 0
            ->where(DB::raw('DATEDIFF(instructor_licenseds.end_date, CURDATE())'), '>', 0)
            ->get();

        return $instructors;
    }
}
