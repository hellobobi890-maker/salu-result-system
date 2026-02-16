@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h1><i class="bi bi-journal-bookmark-fill me-2"></i> Programs</h1>
        <a href="{{ route('admin.programs.create') }}" class="btn btn-dark btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Program
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left:20px">Name</th>
                            <th>Status</th>
                            <th>Students</th>
                            <th class="text-end" style="padding-right:20px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programs as $program)
                            <tr>
                                <td style="padding-left:20px">
                                    <div class="fw-semibold">{{ $program->name }}</div>
                                </td>
                                <td>
                                    @if ($program->is_active)
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">{{ $program->students()->count() }}</span>
                                </td>
                                <td class="text-end" style="padding-right:20px">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size:32px;opacity:0.3"></i>
                                    No programs found. <a href="{{ route('admin.programs.create') }}">Add one</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $programs->links() }}</div>
@endsection
