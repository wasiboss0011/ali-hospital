<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ali's Hospital Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #991b1b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, sans-serif;
            padding: 1rem;
        }
        .login-card {
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: #fff;
            padding: 2.25rem 2rem 2rem;
            text-align: center;
        }
        .login-header h3 {
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .btn-hospital-submit {
            background-color: #1e3a8a;
            color: #fff;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: none;
            transition: all 0.2s;
        }
        .btn-hospital-submit:hover {
            background-color: #0f172a;
            color: #fff;
        }
        .credentials-pill {
            cursor: pointer;
            padding: 0.35rem 0.6rem;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 0.35rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .credentials-pill:hover {
            background: #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="mb-2">
                <i class="bi bi-hospital fs-1 text-danger"></i>
            </div>
            <h3>Ali's Hospital</h3>
            <p class="text-white-50 small mb-0">Campbell Road, Mozzam Nagar, Lucknow</p>
        </div>

        <div class="p-4">
            @if($errors->any())
                <div class="alert alert-danger py-2 small">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', 'admin@hospital.com') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                        <input type="password" id="password" name="password" class="form-control" value="password123" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                        <label class="form-check-label small text-muted" for="remember">Remember me</label>
                    </div>
                    <span class="text-muted small">Local XAMPP</span>
                </div>

                <button type="submit" class="btn btn-hospital-submit w-100 mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Portal
                </button>
            </form>

            <div class="border rounded p-3 bg-light">
                <p class="small fw-bold text-muted mb-2">⚡ Quick 1-Click Role Logins:</p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="credentials-pill" onclick="fillCreds('admin@hospital.com')">
                        <i class="bi bi-shield-lock-fill text-danger me-1"></i>Admin
                    </span>
                    <span class="credentials-pill" onclick="fillCreds('doctor@hospital.com')">
                        <i class="bi bi-person-fill-check text-primary me-1"></i>Doctor
                    </span>
                    <span class="credentials-pill" onclick="fillCreds('staff@hospital.com')">
                        <i class="bi bi-headset text-success me-1"></i>Staff
                    </span>
                </div>
                <small class="text-muted d-block mt-2" style="font-size: 0.7rem;">Default password: <code>password123</code></small>
            </div>
        </div>
    </div>

    <script>
        function fillCreds(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password123';
        }
    </script>
</body>
</html>