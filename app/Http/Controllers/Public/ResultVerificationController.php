<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Student;
use App\Models\Year;
use App\Models\Program;
use Illuminate\Http\Request;

class ResultVerificationController extends Controller
{
    public function showHome(Request $request)
    {
        return view('public.home', [
            'programs' => Program::where('is_active', true)->orderByDesc('id')->get(),
            'years' => Year::where('is_active', true)->orderByDesc('id')->get(),
            'selectedProgramId' => $request->input('program_id'),
            'selectedYearId' => $request->input('year_id'),
            'regNo' => $request->input('reg_no'),
            'result' => null,
            'student' => null,
        ]);
    }

    public function verifyHome(Request $request)
    {
        $data = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'year_id' => ['required', 'exists:years,id'],
            'reg_no' => ['required', 'string', 'max:255'],
        ]);

        $years = Year::where('is_active', true)->orderByDesc('id')->get();
        $programs = Program::where('is_active', true)->orderByDesc('id')->get();

        $student = Student::where('program_id', $data['program_id'])
            ->where('year_id', $data['year_id'])
            ->where('reference_no', $data['reg_no'])
            ->first();

        if (!$student) {
            return view('public.home', [
                'programs' => $programs,
                'years' => $years,
                'selectedProgramId' => $data['program_id'],
                'selectedYearId' => $data['year_id'],
                'regNo' => $data['reg_no'],
                'result' => null,
                'student' => null,
            ])->withErrors([
                'reg_no' => 'Record not found. Please check Program / Year / Reg No.',
            ]);
        }

        $result = Result::where('year_id', $data['year_id'])
            ->where('student_id', $student->id)
            ->first();

        return view('public.home', [
            'programs' => $programs,
            'years' => $years,
            'selectedProgramId' => $data['program_id'],
            'selectedYearId' => $data['year_id'],
            'regNo' => $data['reg_no'],
            'result' => $result,
            'student' => $student,
        ]);
    }

    public function showForm(Request $request)
    {
        return view('public.verify', [
            'programs' => Program::where('is_active', true)->orderByDesc('id')->get(),
            'years' => Year::where('is_active', true)->orderByDesc('id')->get(),
            'selectedYearId' => $request->input('year_id'),
            'selectedProgramId' => $request->input('program_id'),
            'referenceNo' => $request->input('reference_no'),
            'rollNo' => '',
            'result' => null,
            'student' => null,
        ]);
    }

    public function scan(Student $student)
    {
        return redirect()->route('public.home', [
            'program_id' => $student->program_id,
            'year_id' => $student->year_id,
            'reg_no' => $student->reference_no,
        ]);
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'year_id' => ['required', 'exists:years,id'],
            'reference_no' => ['required', 'string', 'max:255'],
            'roll_no' => ['required', 'string', 'max:255'],
        ]);

        $years = Year::where('is_active', true)->orderByDesc('id')->get();
        $programs = Program::where('is_active', true)->orderByDesc('id')->get();

        $student = Student::where('program_id', $data['program_id'])
            ->where('year_id', $data['year_id'])
            ->where('reference_no', $data['reference_no'])
            ->where('roll_no', $data['roll_no'])
            ->first();

        if (!$student) {
            return view('public.verify', [
                'programs' => $programs,
                'years' => $years,
                'selectedYearId' => $data['year_id'],
                'selectedProgramId' => $data['program_id'],
                'referenceNo' => $data['reference_no'],
                'rollNo' => $data['roll_no'],
                'result' => null,
                'student' => null,
            ])->withErrors([
                'reference_no' => 'Record not found. Please check Program / Year / Reference No / Roll No.',
            ]);
        }

        $result = Result::where('year_id', $data['year_id'])
            ->where('student_id', $student->id)
            ->first();

        return view('public.verify', [
            'programs' => $programs,
            'years' => $years,
            'selectedYearId' => $data['year_id'],
            'selectedProgramId' => $data['program_id'],
            'referenceNo' => $data['reference_no'],
            'rollNo' => $data['roll_no'],
            'result' => $result,
            'student' => $student,
        ]);
    }
}
