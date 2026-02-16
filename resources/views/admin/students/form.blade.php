@extends('admin.layout')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.students.index') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to Students
        </a>
    </div>

    <div class="card" style="max-width:650px">
        <div class="card-body">
            <h1 class="h4 mb-3 fw-bold">
                <i class="bi bi-people-fill me-2"></i>
                {{ $student->exists ? 'Edit Student' : 'Add Student' }}
            </h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ $student->exists ? route('admin.students.update', $student) : route('admin.students.store') }}" class="vstack gap-3">
                @csrf
                @if ($student->exists)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Program</label>
                        <select name="program_id" class="form-select">
                            <option value="">Select Program</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" @selected((string)old('program_id', $student->program_id) === (string)$program->id)>{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Year</label>
                        <select name="year_id" class="form-select">
                            <option value="">Select Year</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->id }}" @selected((string)old('year_id', $student->year_id) === (string)$year->id)>{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Student Name</label>
                    <input name="name" value="{{ old('name', $student->name) }}" class="form-control" placeholder="Full name" />
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Roll No</label>
                        <input name="roll_no" value="{{ old('roll_no', $student->roll_no) }}" class="form-control" placeholder="e.g. 17025" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Reference No</label>
                        <input name="reference_no" value="{{ old('reference_no', $student->reference_no) }}" class="form-control" placeholder="e.g. 743-2006-3428" />
                    </div>
                </div>

                {{-- QR Code Section (only for existing student) --}}
                @if ($student->exists)
                    <div class="border rounded-3 p-3 mt-2" style="background:#f8fafc">
                        <div class="fw-semibold mb-2"><i class="bi bi-qr-code me-1"></i> QR Code</div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <img src="{{ $student->qrImageUrl() }}" alt="QR" class="rounded-3 border" style="width:140px;height:140px;" />
                            </div>
                            <div class="col">
                                <div class="text-muted small mb-1">Scan URL</div>
                                <a class="d-block small text-break mb-2" href="{{ $student->qrUrl() }}" target="_blank">{{ $student->qrUrl() }}</a>
                                <a href="{{ $student->qrImageUrl() }}" class="btn btn-dark btn-sm" download="qr-{{ $student->roll_no }}.png">
                                    <i class="bi bi-download me-1"></i> Download QR
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="d-flex gap-2 pt-2">
                    <button class="btn btn-dark" type="submit">
                        <i class="bi bi-check-lg me-1"></i> Save
                    </button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
