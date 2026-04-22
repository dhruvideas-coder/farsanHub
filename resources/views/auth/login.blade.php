<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FarsanHub') }} - Login</title>

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
        html, body {
            overflow-x: hidden;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin: 0;
            width: 100%;
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
            margin: 0 auto;
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
            padding: 0.8rem 1.2rem;
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

        .btn-google-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 0.8rem;
            background-color: white;
            color: var(--dark);
            font-size: 1rem;
            font-weight: 600;
            border: 1.5px solid #e9ecef;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-google-login:hover {
            border-color: var(--primary);
            background-color: #fffaf5;
            color: var(--dark);
            box-shadow: 0 4px 12px rgba(255, 153, 51, 0.1);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #adb5bd;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e9ecef;
        }
        .divider span {
            padding: 0 15px;
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .login-card {
                flex-direction: column;
                max-width: 450px;
                margin: 0 auto;
                min-height: auto;
                width: 100%;
            }
            .login-info {
                display: none !important;
            }
            .login-form-container {
                padding: 2.5rem 2rem;
                border-radius: 24px;
            }
        }
        @media (max-width: 576px) {
            .login-wrapper { 
                padding: 1rem; 
            }
            .login-card {
                max-width: 100%;
                margin: 0 auto;
            }
            .login-form-container { 
                padding: 2rem 1.5rem; 
            }
        }
    </style>
</head>

<body>
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="login-wrapper">
        <div class="login-card">
            
            <!-- Left Info Section -->
            <div class="login-info">
                <div class="login-info-content">
                    <h2>Smart Business Portal</h2>
                    <p>Manage your Farsan business with powerful tools, real-time insights, and easy order tracking. All in one place.</p>
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
                <div class="text-center">
                    <div class="brand-text-logo">
                        <span class="farsan">Farsan</span><span class="hub">Hub</span>
                    </div>
                    <p class="welcome-text">Welcome back! Please login to your account.</p>
                </div>

                <a href="{{ route('auth.google') }}" class="btn-google-login">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                    Continue with Google
                </a>

                <div class="divider">
                    <span>or sign in with email</span>
                </div>

                @if(session('error'))
                <div class="alert alert-danger py-2 border-0 rounded-3" style="font-size: 0.9rem;">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="password" class="form-label mb-0">Password</label>
                        </div>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder="••••••••">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary d-flex justify-content-center align-items-center gap-2">
                            Sign In
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
