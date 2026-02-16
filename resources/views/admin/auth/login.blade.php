<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/views/admin-login.css') }}">
</head>
<body>
    <div class="auth-wrap">
        <div class="card shadow-sm border-0 auth-card">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="auth-logo mb-3">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div class="auth-badge">Admin Portal</div>
                    <h1 class="h4 fw-bold mb-1">Welcome back</h1>
                    <div class="text-muted small">Sign in to manage the result portal</div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="vstack gap-3">
                    @csrf

                    <div>
                        <label class="form-label">Email address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                            <input name="email" type="email" value="{{ old('email') }}" class="form-control" placeholder="admin@example.com" />
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                            <input name="password" type="password" class="form-control" placeholder="••••••••" />
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1" />
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>

                    <button class="btn btn-primary w-100 py-2" type="submit">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
