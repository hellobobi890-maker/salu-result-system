<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Student;
use App\Models\Year;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResultController extends Controller
{
    public function studentsForSelection(Request $request): JsonResponse
    {
        $data = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'year_id' => ['required', 'exists:years,id'],
        ]);

        $students = Student::query()
            ->where('program_id', $data['program_id'])
            ->where('year_id', $data['year_id'])
            ->orderBy('name')
            ->get(['id', 'name', 'roll_no', 'reference_no']);

        return response()->json([
            'students' => $students,
        ]);
    }

    public function index(Request $request)
    {
        $query = Result::query()->with(['year', 'student.program']);

        if ($request->filled('program_id')) {
            $query->whereHas('student', function ($sub) use ($request) {
                $sub->where('program_id', $request->input('program_id'));
            });
        }

        if ($request->filled('year_id')) {
            $query->where('year_id', $request->input('year_id'));
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->whereHas('student', function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('roll_no', 'like', "%{$q}%")
                    ->orWhere('reference_no', 'like', "%{$q}%");
            });
        }

        return view('admin.results.index', [
            'results' => $query->orderByDesc('id')->paginate(20)->withQueryString(),
            'years' => Year::orderByDesc('id')->get(),
            'programs' => Program::orderByDesc('id')->get(),
        ]);
    }

    public function create()
    {
        $programId = request()->input('program_id');
        $yearId = request()->input('year_id');

        $students = collect();
        if ($programId && $yearId) {
            $students = Student::where('program_id', $programId)
                ->where('year_id', $yearId)
                ->orderByDesc('id')
                ->get();
        }

        return view('admin.results.form', [
            'result' => new Result(),
            'programs' => Program::orderByDesc('id')->get(),
            'years' => Year::orderByDesc('id')->get(),
            'students' => $students,
            'selectedProgramId' => $programId,
            'selectedYearId' => $yearId,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'year_id' => ['required', 'exists:years,id'],
            'student_id' => ['required', 'exists:students,id'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:255'],
            'marks' => ['nullable', 'string', 'max:255'],
            'result_json' => ['nullable', 'string'],
        ]);

        $validStudent = Student::where('id', $data['student_id'])
            ->where('program_id', $data['program_id'])
            ->where('year_id', $data['year_id'])
            ->exists();

        if (!$validStudent) {
            return back()->withErrors(['student_id' => 'Selected student does not match Program/Year.'])->withInput();
        }

        $resultData = [];

        if (!empty($data['father_name'])) {
            $resultData['FATHER NAME'] = $data['father_name'];
        }
        if (!empty($data['type'])) {
            $resultData['TYPE'] = $data['type'];
        }
        if (!empty($data['gender'])) {
            $resultData['GENDER'] = $data['gender'];
        }
        if (!empty($data['marks'])) {
            $resultData['MARKS'] = $data['marks'];
        }

        if (!empty($data['result_json'])) {
            $decoded = json_decode($data['result_json'], true);
            if (!is_array($decoded)) {
                return back()->withErrors(['result_json' => 'Invalid JSON'])->withInput();
            }
            $resultData = array_merge($resultData, $decoded);
        }

        if (empty($resultData)) {
            return back()->withErrors(['marks' => 'Please fill at least one result field.'])->withInput();
        }

        Result::updateOrCreate(
            [
                'year_id' => (int) $data['year_id'],
                'student_id' => (int) $data['student_id'],
            ],
            [
                'result_data' => $resultData,
            ]
        );

        return redirect()->route('admin.results.index');
    }

    public function edit(Result $result)
    {
        $result->load(['year', 'student.program']);
        $programId = $result->student?->program_id;
        $students = collect();
        if ($programId) {
            $students = Student::where('program_id', $programId)
                ->where('year_id', $result->year_id)
                ->orderByDesc('id')
                ->get();
        }

        return view('admin.results.form', [
            'result' => $result,
            'programs' => Program::orderByDesc('id')->get(),
            'years' => Year::orderByDesc('id')->get(),
            'students' => $students,
        ]);
    }

    public function update(Request $request, Result $result)
    {
        $data = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'year_id' => ['required', 'exists:years,id'],
            'student_id' => ['required', 'exists:students,id'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:255'],
            'marks' => ['nullable', 'string', 'max:255'],
            'result_json' => ['nullable', 'string'],
        ]);

        $validStudent = Student::where('id', $data['student_id'])
            ->where('program_id', $data['program_id'])
            ->where('year_id', $data['year_id'])
            ->exists();

        if (!$validStudent) {
            return back()->withErrors(['student_id' => 'Selected student does not match Program/Year.'])->withInput();
        }

        $resultData = [];

        if (!empty($data['father_name'])) {
            $resultData['FATHER NAME'] = $data['father_name'];
        }
        if (!empty($data['type'])) {
            $resultData['TYPE'] = $data['type'];
        }
        if (!empty($data['gender'])) {
            $resultData['GENDER'] = $data['gender'];
        }
        if (!empty($data['marks'])) {
            $resultData['MARKS'] = $data['marks'];
        }

        if (!empty($data['result_json'])) {
            $decoded = json_decode($data['result_json'], true);
            if (!is_array($decoded)) {
                return back()->withErrors(['result_json' => 'Invalid JSON'])->withInput();
            }
            $resultData = array_merge($resultData, $decoded);
        }

        if (empty($resultData)) {
            return back()->withErrors(['marks' => 'Please fill at least one result field.'])->withInput();
        }

        $result->update([
            'year_id' => (int) $data['year_id'],
            'student_id' => (int) $data['student_id'],
            'result_data' => $resultData,
        ]);

        return redirect()->route('admin.results.index');
    }

    public function destroy(Result $result)
    {
        $result->delete();

        return redirect()->route('admin.results.index');
    }
}
