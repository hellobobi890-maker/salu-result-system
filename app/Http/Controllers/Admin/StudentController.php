<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Year;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query()->with('year', 'program');

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->input('program_id'));
        }

        if ($request->filled('year_id')) {
            $query->where('year_id', $request->input('year_id'));
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('roll_no', 'like', "%{$q}%")
                    ->orWhere('reference_no', 'like', "%{$q}%");
            });
        }

        return view('admin.students.index', [
            'students' => $query->orderByDesc('id')->paginate(20)->withQueryString(),
            'years' => Year::orderByDesc('id')->get(),
            'programs' => Program::orderByDesc('id')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.students.form', [
            'student' => new Student(),
            'years' => Year::orderByDesc('id')->get(),
            'programs' => Program::orderByDesc('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'year_id' => ['required', 'exists:years,id'],
            'name' => ['required', 'string', 'max:255'],
            'roll_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('students', 'roll_no')->where(fn ($q) => $q
                    ->where('program_id', $request->input('program_id'))
                    ->where('year_id', $request->input('year_id'))
                ),
            ],
            'reference_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('students', 'reference_no')->where(fn ($q) => $q
                    ->where('program_id', $request->input('program_id'))
                    ->where('year_id', $request->input('year_id'))
                ),
            ],
        ]);

        Student::create($data);

        return redirect()->route('admin.students.index');
    }

    public function edit(Student $student)
    {
        return view('admin.students.form', [
            'student' => $student,
            'years' => Year::orderByDesc('id')->get(),
            'programs' => Program::orderByDesc('id')->get(),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'year_id' => ['required', 'exists:years,id'],
            'name' => ['required', 'string', 'max:255'],
            'roll_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('students', 'roll_no')
                    ->ignore($student->id)
                    ->where(fn ($q) => $q
                        ->where('program_id', $request->input('program_id'))
                        ->where('year_id', $request->input('year_id'))
                    ),
            ],
            'reference_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('students', 'reference_no')
                    ->ignore($student->id)
                    ->where(fn ($q) => $q
                        ->where('program_id', $request->input('program_id'))
                        ->where('year_id', $request->input('year_id'))
                    ),
            ],
        ]);

        $student->update($data);

        return redirect()->route('admin.students.index');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index');
    }
}
