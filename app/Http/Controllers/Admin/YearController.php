<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Year;
use Illuminate\Http\Request;

class YearController extends Controller
{
    public function index()
    {
        return view('admin.years.index', [
            'years' => Year::orderByDesc('id')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.years.form', [
            'year' => new Year(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:years,name'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        Year::create($data);

        return redirect()->route('admin.years.index');
    }

    public function edit(Year $year)
    {
        return view('admin.years.form', [
            'year' => $year,
        ]);
    }

    public function update(Request $request, Year $year)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:years,name,'.$year->id],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $year->update($data);

        return redirect()->route('admin.years.index');
    }

    public function destroy(Year $year)
    {
        $year->delete();

        return redirect()->route('admin.years.index');
    }
}
