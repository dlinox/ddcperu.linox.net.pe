<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\InstructorLicensed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorLicensedController extends Controller
{
    protected $instructor;
    protected $instructorLicensed;

    public function __construct()
    {
        $this->instructor = new Instructor();
        $this->instructorLicensed = new InstructorLicensed();
    }

    public function index(Request $request, $id)
    {

        $perPage = $request->input('perPage', 10);

        $query = $this->instructorLicensed->query();

        $query->join('courses', 'courses.id', '=', 'instructor_licenseds.course_id')
            ->select(
                'instructor_licenseds.*',
                'courses.name as course',
                DB::raw("CONCAT(DATE_FORMAT(instructor_licenseds.start_date, '%d/%m/%Y') , ' - ' ,DATE_FORMAT(instructor_licenseds.end_date, '%d/%m/%Y')) as validity_period"),
                DB::raw("DATEDIFF(instructor_licenseds.end_date, CURDATE()) as days_remaining"),
            )
            ->where('instructor_id', $id);

        if ($request->has('search')) {
            $query->where('courses.name', 'LIKE', "%{$request->search}%");
        }

        $items = $query->paginate($perPage)->appends($request->query());

        $instructor = $this->instructor->select(DB::raw('CONCAT(name, " ", last_name) as name'))->find($id);

        return inertia(
            'admin/instructors/license',
            [
                'id' => $id,
                'title' => $instructor->name,
                'subtitle' => 'Gestión de licencias del instructor',
                'items' => $items,
                'filters' => [
                    'search' => $request->search,
                    'perPage' => $perPage,
                ],
                'headers' => $this->instructorLicensed->headers,
                'courses' => Course::select('id', 'name')->get(),
            ]
        );
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $courseId = $request->course_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $overlap = $this->instructorLicensed
            ->where('course_id', $courseId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors([
                'exception' => 'El instructor ya tiene una licencia activa para este curso en el rango de fechas seleccionado.',
                'error' => 'El instructor ya tiene una licencia activa para este curso en el rango de fechas seleccionado.'
            ]);
        }


        $this->instructorLicensed->create([
            'instructor_id' => $id,
            'course_id' => $request->course_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->back()->with('success', 'Licencia creada correctamente');
    }

    public function update(Request $request, $id, $license_id)
    {



        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $courseId = $request->course_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $overlap = $this->instructorLicensed
            ->where('course_id', $courseId)
            ->where('id', '!=', $license_id)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors([
                'exception' => 'El instructor ya tiene una licencia activa para este curso en el rango de fechas seleccionado.',
                'error' => 'El instructor ya tiene una licencia activa para este curso en el rango de fechas seleccionado.'
            ]);
        }



        $this->instructorLicensed->find($license_id)->update([
            'course_id' => $request->course_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->back()->with('success', 'Licencia actualizada correctamente');
    }

    public function destroy($id, $license_id)
    {
        $this->instructorLicensed->find($license_id)->delete();

        return redirect()->back()->with('success', 'Licencia eliminada correctamente');
    }
}
