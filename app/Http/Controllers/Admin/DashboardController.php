<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Student;
use App\Models\Year;
use App\Models\Program;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'programsCount' => Program::count(),
            'yearsCount' => Year::count(),
            'studentsCount' => Student::count(),
            'resultsCount' => Result::count(),
            'latestStudents' => Student::query()->with(['program', 'year'])->orderByDesc('id')->limit(20)->get(),
        ]);
    }
}
