@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h1><i class="bi bi-clipboard-data-fill me-2"></i> Results</h1>
        <a href="{{ route('admin.results.create') }}" class="btn btn-dark btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Result
        </a>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label">Program</label>
                    <select name="program_id" class="form-select form-select-sm">
                        <option value="">All Programs</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}" @selected((string)request('program_id') === (string)$program->id)>{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label">Year</label>
                    <select name="year_id" class="form-select form-select-sm">
                        <option value="">All Years</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->id }}" @selected((string)request('year_id') === (string)$year->id)>{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search student name/roll/ref" />
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <button class="btn btn-dark btn-sm w-100" type="submit">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
                @if(request()->hasAny(['program_id','year_id','q']))
                    <div class="col-auto">
                        <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-lg me-1"></i> Clear
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                    <tr>
                        <th style="padding-left:20px">#</th>
                        <th>Program</th>
                        <th>Year</th>
                        <th>Student</th>
                        <th>Roll No</th>
                        <th>Reference No</th>
                        <th class="text-end" style="padding-right:20px">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($results as $result)
                        <tr>
                            <td class="text-muted" style="padding-left:20px">{{ $result->id }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $result->student->program->name }}</span>
                            </td>
                            <td>{{ $result->year->name }}</td>
                            <td class="fw-semibold">{{ $result->student->name }}</td>
                            <td>{{ $result->student->roll_no }}</td>
                            <td><code>{{ $result->student->reference_no }}</code></td>
                            <td class="text-end" style="padding-right:20px">
                                <div class="d-inline-flex gap-1">
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.results.edit', $result) }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.results.destroy', $result) }}" onsubmit="return confirm('Delete this result?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox d-block mb-2" style="font-size:32px;opacity:0.3"></i>
                                No results found. <a href="{{ route('admin.results.create') }}">Add one</a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $results->links() }}</div>
@endsection
