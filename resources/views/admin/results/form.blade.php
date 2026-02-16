@extends('admin.layout')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.results.index') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to Results
        </a>
    </div>

    <div class="card" style="max-width:700px">
        <div class="card-body">
            <h1 class="h4 mb-3 fw-bold">
                <i class="bi bi-clipboard-data-fill me-2"></i>
                {{ $result->exists ? 'Edit Result' : 'Add Result' }}
            </h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ $result->exists ? route('admin.results.update', $result) : route('admin.results.store') }}" class="vstack gap-3">
                @csrf
                @if ($result->exists)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Program</label>
                        <select id="program_id_select" name="program_id" class="form-select">
                            <option value="">Select Program</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" @selected((string)old('program_id', $result->exists ? $result->student?->program_id : ($selectedProgramId ?? '')) === (string)$program->id)>{{ $program->name }}</option>
                            @endforeach
                        </select>
                        @if(!$result->exists)
                            <div class="form-text">Select program + year to load students</div>
                        @endif
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Year</label>
                        <select id="year_id_select" name="year_id" class="form-select">
                            <option value="">Select Year</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->id }}" @selected((string)old('year_id', $result->exists ? $result->year_id : ($selectedYearId ?? '')) === (string)$year->id)>{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Student</label>
                    <select id="student_id_select" name="student_id" class="form-select" @disabled(!$result->exists && empty($selectedProgramId) && empty($selectedYearId))>
                        <option value="">Select Student</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" @selected((string)old('student_id', $result->student_id) === (string)$student->id)>
                                {{ $student->name }} (Roll: {{ $student->roll_no }}, Ref: {{ $student->reference_no }})
                            </option>
                        @endforeach
                    </select>
                    @if(!$result->exists && (empty($selectedProgramId) || empty($selectedYearId)))
                        <div class="form-text">Select Program and Year first</div>
                    @endif
                </div>

                <hr class="my-1">
                <div class="fw-semibold text-muted small text-uppercase">Result Data</div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Father Name</label>
                        <input name="father_name" value="{{ old('father_name', $result->result_data['FATHER NAME'] ?? '') }}" class="form-control" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Type</label>
                        <input name="type" value="{{ old('type', $result->result_data['TYPE'] ?? '') }}" class="form-control" placeholder="Regular / Private" />
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Gender</label>
                        <input name="gender" value="{{ old('gender', $result->result_data['GENDER'] ?? '') }}" class="form-control" placeholder="Male / Female" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Marks / CGPA</label>
                        <input name="marks" value="{{ old('marks', $result->result_data['MARKS'] ?? '') }}" class="form-control" placeholder="CGPA : 3.1 / 4.00" />
                    </div>
                </div>

                <div>
                    <label class="form-label">Extra Result JSON <span class="text-muted fw-normal">(Optional)</span></label>
                    <textarea name="result_json" rows="4" class="form-control font-monospace" placeholder='{"STATUS":"Pass"}'  style="font-size:13px;">{{ old('result_json', '') }}</textarea>
                    <div class="form-text">Add custom key/value pairs as JSON. Example: {"STATUS":"Pass","DIVISION":"First"}</div>
                </div>

                <div class="d-flex gap-2 pt-2">
                    <button class="btn btn-dark" type="submit">
                        <i class="bi bi-check-lg me-1"></i> Save
                    </button>
                    <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @if(!$result->exists)
        <script>
            (function () {
                const programSelect = document.getElementById('program_id_select');
                const yearSelect = document.getElementById('year_id_select');
                const studentSelect = document.getElementById('student_id_select');
                if (!programSelect || !yearSelect || !studentSelect) return;

                const endpoint = @json(route('admin.results.students'));
                const oldStudentId = @json((string) old('student_id', ''));

                function setLoadingState(isLoading) {
                    studentSelect.disabled = isLoading || !programSelect.value || !yearSelect.value;
                }

                function resetStudents(message) {
                    studentSelect.innerHTML = '';
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = message;
                    studentSelect.appendChild(opt);
                }

                async function loadStudents() {
                    const programId = programSelect.value;
                    const yearId = yearSelect.value;

                    if (!programId || !yearId) {
                        resetStudents('Select Student');
                        setLoadingState(false);
                        return;
                    }

                    setLoadingState(true);
                    resetStudents('Loading...');

                    try {
                        const url = endpoint + '?program_id=' + encodeURIComponent(programId) + '&year_id=' + encodeURIComponent(yearId);
                        const res = await fetch(url, {
                            method: 'GET',
                            credentials: 'same-origin',
                            headers: { 'Accept': 'application/json' },
                        });

                        if (!res.ok) throw new Error('Request failed');

                        const payload = await res.json();
                        resetStudents('Select Student');

                        const list = payload.students || [];
                        if (!Array.isArray(list) || list.length === 0) {
                            resetStudents('No students found');
                            return;
                        }

                        list.forEach(function (s) {
                            const opt = document.createElement('option');
                            opt.value = String(s.id);
                            opt.textContent = s.name + ' (Roll: ' + (s.roll_no || '-') + ', Ref: ' + (s.reference_no || '-') + ')';
                            if (oldStudentId && String(s.id) === oldStudentId) {
                                opt.selected = true;
                            }
                            studentSelect.appendChild(opt);
                        });
                    } catch (e) {
                        resetStudents('Select Student');
                    } finally {
                        setLoadingState(false);
                    }
                }

                programSelect.addEventListener('change', loadStudents);
                yearSelect.addEventListener('change', loadStudents);
                loadStudents();
            })();
        </script>
    @endif
@endsection
