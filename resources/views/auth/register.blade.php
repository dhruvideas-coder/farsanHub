<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FarsanHub') }} - Register</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/farsan-favicon.svg') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <style>
        :root {
            --primary: #FF9933;
            --primary-dark: #e07a1a;
            --dark: #3d2200;
            --bg-color: #f8f9fa;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.5);
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            margin: 0;
            padding: 2rem 0; /* Add padding for long register form */
        }
        /* Animated Background Elements */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            animation: float 10s infinite ease-in-out alternate;
        }
        .shape-1 {
            width: 400px;
            height: 400px;
            background: rgba(255, 153, 51, 0.3);
            top: -100px;
            right: -100px;
        }
        .shape-2 {
            width: 500px;
            height: 500px;
            background: rgba(61, 34, 0, 0.1);
            bottom: -150px;
            left: -150px;
            animation-delay: -5s;
        }
        @keyframes float {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(-50px) scale(1.1); }
        }

        .login-wrapper {
            width: 100%;
            max-width: 1000px;
            padding: 2rem;
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 100%;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            display: flex;
            flex-direction: row;
            min-height: 550px;
        }

        .login-info {
            background: linear-gradient(135deg, var(--dark), #2a1700);
            padding: 3rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .login-info::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .login-info-content {
            position: relative;
            z-index: 2;
        }

        .login-info h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .login-info p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .login-form-container {
            padding: 3rem 4rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .brand-text-logo {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
            display: inline-block;
        }
        .brand-text-logo .farsan { color: var(--primary); }
        .brand-text-logo .hub { color: var(--dark); }

        .welcome-text {
            color: #6c757d;
            margin-bottom: 2rem;
            font-size: 1rem;
        }

        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 0.7rem 1.2rem;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            background-color: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255, 153, 51, 0.15);
        }
        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(255, 153, 51, 0.2);
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .login-card {
                flex-direction: column;
                max-width: 450px;
                margin: 0 auto;
                min-height: auto;
            }
            .login-info {
                display: none !important;
            }
            .login-form-container {
                padding: 2.5rem 2rem;
                border-radius: 24px;
            }
            body { padding: 0; }
            .login-wrapper { padding: 1rem; }
        }
        @media (max-width: 576px) {
            .login-form-container { padding: 2rem 1.5rem; }
        }
    </style>
</head>

<body>
    <div class="bg-shape shape-1"></div>
    {{-- <div class="bg-shape shape-2"></div> --}}

    <div class="login-wrapper">
        <div class="login-card">
            
            <!-- Left Info Section -->
            <div class="login-info">
                <div class="login-info-content">
                    <h2>Join FarsanHub</h2>
                    <p>Create an account to start managing your Farsan business with powerful tools, real-time insights, and easy order tracking. All in one place.</p>
                    <div class="mt-4 d-flex gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-warning"></i>
                            <span>Secure</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-warning"></i>
                            <span>Fast</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-warning"></i>
                            <span>Reliable</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Section -->
            <div class="login-form-container">
                <div class="text-center mb-4">
                    <div class="brand-text-logo">
                        <span class="farsan">Farsan</span><span class="hub">Hub</span>
                    </div>
                    <p class="welcome-text">Create a new admin account.</p>
                </div>

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name') }}" placeholder="Rameshbhai Patel">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder="••••••••">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control"
                            id="password_confirmation" name="password_confirmation" placeholder="••••••••">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary d-flex justify-content-center align-items-center gap-2">
                            Create Account
                            <i class="bi bi-person-plus-fill"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <p class="mb-0" style="font-size: 0.9rem; color: #6c757d;">
                        Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color: var(--primary);">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
