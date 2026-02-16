@extends('admin.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/views/admin-dashboard.css') }}">
@endpush

@section('content')
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1">Dashboard</h1>
        <div class="text-muted small">Welcome back! Here's your overview.</div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <a class="stat-card stat-programs" href="{{ route('admin.programs.index') }}">
                <i class="bi bi-journal-bookmark-fill stat-icon"></i>
                <div class="stat-label">Programs</div>
                <div class="stat-value">{{ $programsCount }}</div>
                <div class="stat-action">View all &rarr;</div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="stat-card stat-years" href="{{ route('admin.years.index') }}">
                <i class="bi bi-calendar3 stat-icon"></i>
                <div class="stat-label">Years</div>
                <div class="stat-value">{{ $yearsCount }}</div>
                <div class="stat-action">View all &rarr;</div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="stat-card stat-students" href="{{ route('admin.students.index') }}">
                <i class="bi bi-people-fill stat-icon"></i>
                <div class="stat-label">Students</div>
                <div class="stat-value">{{ $studentsCount }}</div>
                <div class="stat-action">View all &rarr;</div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="stat-card stat-results" href="{{ route('admin.results.index') }}">
                <i class="bi bi-clipboard-data-fill stat-icon"></i>
                <div class="stat-label">Results</div>
                <div class="stat-value">{{ $resultsCount }}</div>
                <div class="stat-action">View all &rarr;</div>
            </a>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="fw-semibold mb-3"><i class="bi bi-lightning-fill text-warning me-1"></i> Quick Actions</div>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-dark btn-sm" href="{{ route('admin.programs.create') }}">
                    <i class="bi bi-plus-lg me-1"></i> Add Program
                </a>
                <a class="btn btn-dark btn-sm" href="{{ route('admin.years.create') }}">
                    <i class="bi bi-plus-lg me-1"></i> Add Year
                </a>
                <a class="btn btn-dark btn-sm" href="{{ route('admin.students.create') }}">
                    <i class="bi bi-plus-lg me-1"></i> Add Student
                </a>
                <a class="btn btn-dark btn-sm" href="{{ route('admin.results.create') }}">
                    <i class="bi bi-plus-lg me-1"></i> Add Result
                </a>
            </div>
        </div>
    </div>

    {{-- Latest Students --}}
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="fw-semibold"><i class="bi bi-clock-history me-1"></i> Recent Students</div>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.students.index') }}">View All</a>
            </div>

            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Program</th>
                        <th>Year</th>
                        <th>Name</th>
                        <th>Roll No</th>
                        <th>Reference No</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($latestStudents as $student)
                        <tr>
                            <td class="text-muted">{{ $student->id }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ optional($student->program)->name }}</span>
                            </td>
                            <td>{{ optional($student->year)->name }}</td>
                            <td class="fw-semibold">{{ $student->name }}</td>
                            <td>{{ $student->roll_no }}</td>
                            <td><code>{{ $student->reference_no }}</code></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox d-block mb-2" style="font-size:28px;opacity:0.3"></i>
                                No students found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
