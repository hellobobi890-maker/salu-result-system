@extends('admin.layout')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.programs.index') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to Programs
        </a>
    </div>

    <div class="card" style="max-width:600px">
        <div class="card-body">
            <h1 class="h4 mb-3 fw-bold">
                <i class="bi bi-journal-bookmark-fill me-2"></i>
                {{ $program->exists ? 'Edit Program' : 'Add Program' }}
            </h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ $program->exists ? route('admin.programs.update', $program) : route('admin.programs.store') }}" class="vstack gap-3">
                @csrf
                @if ($program->exists)
                    @method('PUT')
                @endif

                <div>
                    <label class="form-label">Program Name</label>
                    <input name="name" type="text" value="{{ old('name', $program->name) }}" class="form-control" placeholder="e.g. BS Computer Science" />
                </div>

                <div class="form-check">
                    <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $program->is_active ?? true)) />
                    <label class="form-check-label" for="is_active">Active (visible on public site)</label>
                </div>

                <div class="d-flex gap-2 pt-2">
                    <button class="btn btn-dark" type="submit">
                        <i class="bi bi-check-lg me-1"></i> {{ $program->exists ? 'Update' : 'Save' }}
                    </button>
                    <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
