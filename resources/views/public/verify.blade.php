@extends('public.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/views/public-verify.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
        <h1 class="h4 mb-3">Result Intimation</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('public.verify') }}" class="vstack gap-3">
            @csrf

            <div>
                <label class="form-label">Program</label>
                <select name="program_id" class="form-select">
                    <option value="">Select Program</option>
                    @foreach ($programs as $program)
                        <option value="{{ $program->id }}" @selected((string)$selectedProgramId === (string)$program->id)>{{ $program->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Year</label>
                <select name="year_id" class="form-select">
                    <option value="">Select Year</option>
                    @foreach ($years as $year)
                        <option value="{{ $year->id }}" @selected((string)$selectedYearId === (string)$year->id)>{{ $year->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Reference No</label>
                <input name="reference_no" value="{{ old('reference_no', $referenceNo) }}" class="form-control" placeholder="Reference No" />
            </div>

            <div>
                <label class="form-label">Roll No</label>
                <input name="roll_no" value="{{ old('roll_no', $rollNo) }}" class="form-control" placeholder="Roll No" />
            </div>

            <button class="btn btn-dark" type="submit">Check Result</button>
        </form>

        @if ($student)
            <div class="mt-4">
                <div class="mb-3">
                    <div class="fw-semibold">Student</div>
                    <div>{{ $student->name }}</div>
                    <div class="text-muted">Roll: {{ $student->roll_no }} | Ref: {{ $student->reference_no }}</div>
                </div>

                @if ($result)
                    <div>
                        <div class="fw-semibold mb-2">Result</div>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                @foreach ($result->result_data as $key => $value)
                                    <tr>
                                        <td class="fw-semibold">{{ $key }}</td>
                                        <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mt-3">
                        Result record abhi add nahi hua.
                    </div>
                @endif
            </div>
        @endif
        </div>
    </div>
@endsection
