@extends('admin.layout')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.years.index') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to Years
        </a>
    </div>

    <div class="card" style="max-width:600px">
        <div class="card-body">
            <h1 class="h4 mb-3 fw-bold">
                <i class="bi bi-calendar3 me-2"></i>
                {{ $year->exists ? 'Edit Year' : 'Add Year' }}
            </h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ $year->exists ? route('admin.years.update', $year) : route('admin.years.store') }}" class="vstack gap-3">
                @csrf
                @if ($year->exists)
                    @method('PUT')
                @endif

                <div>
                    <label class="form-label">Name</label>
                    <input name="name" value="{{ old('name', $year->name) }}" class="form-control" placeholder="e.g. 2024" />
                </div>

                <div class="form-check">
                    <input id="is_active" name="is_active" type="checkbox" value="1" class="form-check-input" @checked(old('is_active', $year->is_active ?? true)) />
                    <label for="is_active" class="form-check-label">Active (visible on public site)</label>
                </div>

                <div class="d-flex gap-2 pt-2">
                    <button class="btn btn-dark" type="submit">
                        <i class="bi bi-check-lg me-1"></i> Save
                    </button>
                    <a href="{{ route('admin.years.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
