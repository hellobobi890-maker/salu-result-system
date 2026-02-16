@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h1><i class="bi bi-calendar3 me-2"></i> Years</h1>
        <a href="{{ route('admin.years.create') }}" class="btn btn-dark btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Year
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left:20px">#</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th class="text-end" style="padding-right:20px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($years as $year)
                            <tr>
                                <td class="text-muted" style="padding-left:20px">{{ $year->id }}</td>
                                <td class="fw-semibold">{{ $year->name }}</td>
                                <td>
                                    @if ($year->is_active)
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end" style="padding-right:20px">
                                    <div class="d-inline-flex gap-2">
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.years.edit', $year) }}">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.years.destroy', $year) }}" onsubmit="return confirm('Delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm" type="submit">
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
                                    No years found. <a href="{{ route('admin.years.create') }}">Add one</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $years->links() }}</div>
@endsection
