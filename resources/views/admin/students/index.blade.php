@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h1><i class="bi bi-people-fill me-2"></i> Students</h1>
        <a href="{{ route('admin.students.create') }}" class="btn btn-dark btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Student
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
                        <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Name, Roll No, or Ref No" />
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <button class="btn btn-dark btn-sm w-100" type="submit">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
                @if(request()->hasAny(['program_id','year_id','q']))
                    <div class="col-auto">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-sm">
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
                        <th>Name</th>
                        <th>Roll No</th>
                        <th>Reference No</th>
                        <th>QR Code</th>
                        <th class="text-end" style="padding-right:20px">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td class="text-muted" style="padding-left:20px">{{ $student->id }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $student->program->name }}</span>
                            </td>
                            <td>{{ $student->year->name }}</td>
                            <td class="fw-semibold">{{ $student->name }}</td>
                            <td>{{ $student->roll_no }}</td>
                            <td><code>{{ $student->reference_no }}</code></td>
                            <td>
                                <img
                                    src="{{ $student->qrImageUrl() }}"
                                    alt="QR"
                                    class="qr-thumb"
                                    data-bs-toggle="modal"
                                    data-bs-target="#qrModal"
                                    onclick="showQrModal('{{ $student->name }}', '{{ $student->qrImageUrl() }}', '{{ $student->qrUrl() }}')"
                                    title="Click to view QR"
                                />
                            </td>
                            <td class="text-end" style="padding-right:20px">
                                <div class="d-inline-flex gap-1">
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.students.edit', $student) }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Delete this student?')">
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
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox d-block mb-2" style="font-size:32px;opacity:0.3"></i>
                                No students found. <a href="{{ route('admin.students.create') }}">Add one</a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $students->links() }}</div>

    {{-- QR Modal --}}
    <div class="modal fade" id="qrModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content" style="border-radius:16px; border:none;">
                <div class="modal-body text-center py-4 px-4">
                    <div class="fw-bold mb-1" id="qrModalName"></div>
                    <div class="text-muted small mb-3">Scan this QR to verify result</div>
                    <img id="qrModalImg" src="" alt="QR Code" class="qr-modal-img mb-3" />
                    <div class="d-grid gap-2">
                        <a id="qrModalLink" href="#" class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Open Scan URL
                        </a>
                        <a id="qrModalDownload" href="#" class="btn btn-dark btn-sm" download="qr-code.png">
                            <i class="bi bi-download me-1"></i> Download QR
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showQrModal(name, imgUrl, scanUrl) {
            document.getElementById('qrModalName').textContent = name;
            document.getElementById('qrModalImg').src = imgUrl;
            document.getElementById('qrModalLink').href = scanUrl;
            document.getElementById('qrModalDownload').href = imgUrl;
        }
    </script>
@endsection
