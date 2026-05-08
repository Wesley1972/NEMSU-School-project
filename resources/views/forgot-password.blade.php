<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Eco-Track</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .hero-section {
            background: linear-gradient(135deg, #1b4332 0%, #2d6a4f 100%);
            color: white;
            padding: 60px 0 50px;
            border-bottom: 5px solid #cddc39;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-success {
            background-color: #2d6a4f;
            border: none;
            font-weight: 600;
        }

        .btn-success:hover {
            background-color: #1b4332;
        }

        .form-control:focus {
            border-color: #2d6a4f;
            box-shadow: 0 0 0 0.25 row rgba(45, 106, 79, 0.25);
        }
    </style>
</head>

<body>

    <nav class="navbar bg-white shadow-sm py-3">
        <div class="container d-flex justify-content-center">
            <a class="navbar-brand fw-bold text-success fs-3" href="{{ route('homepage') }}">🌿 ECO-TRACK</a>
        </div>
    </nav>

    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-6 fw-bold mb-2">Account Recovery</h1>
            <p class="lead mx-auto mb-0" style="max-width: 600px;">Forgot your password? No worries. Enter your email
                and we'll send you a link to reset it.</p>
        </div>
    </header>

    <div class="container my-auto py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">

                @if (session('status'))
                    <div class="alert alert-success border-0 shadow-sm mb-4 fw-bold small text-center" role="alert">
                        {{ session('status') }}

                        <!-- This will only show up if we pass 'test_link' from the controller -->
                        @if (session('test_link'))
                            <a href="{{ session('test_link') }}" class="text-success text-decoration-underline ms-1">
                                (Testing: Click here to reset)
                            </a>
                        @endif
                    </div>
                @endif

                <div class="card p-4 p-md-5 border-top border-success border-4">
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf

                        <div class="mb-4 text-center">
                            <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex justify-content-center align-items-center mb-3"
                                style="width: 70px; height: 70px;">
                                <span class="fs-2">🔑</span>
                            </div>
                            <h4 class="fw-bold text-dark">Password Reset</h4>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label small fw-bold text-secondary">Email Address</label>
                            <input type="email" name="email" id="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                placeholder="name@example.com" value="{{ old('email') }}" required autofocus>

                            @error('email')
                                <div class="invalid-feedback fw-bold">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill shadow-sm py-3">
                            Send Reset Link
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none text-muted small fw-bold">
                            ← Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-auto py-4 text-center text-muted small">
        <div class="container">
            &copy; {{ date('Y') }} Eco-Track Environmental Systems. All rights reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
