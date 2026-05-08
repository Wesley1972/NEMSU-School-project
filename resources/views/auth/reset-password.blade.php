<div>
    <!-- Because you are alive, everything is possible. - Thich Nhat Hanh -->
</div>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Password | Eco-Track</title>
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
            box-shadow: 0 0 0 0.25rem rgba(45, 106, 79, 0.25);
        }
    </style>
</head>

<body>

    <nav class="navbar bg-white shadow-sm py-3">
        <div class="container d-flex justify-content-center">
            <a class="navbar-brand fw-bold text-success fs-3" href="{{ route('homepage') ?? '#' }}">🌿 ECO-TRACK</a>
        </div>
    </nav>

    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-6 fw-bold mb-2">Secure Your Account</h1>
            <p class="lead mx-auto mb-0" style="max-width: 600px;">Almost there! Please enter your new password below to
                regain access to your Eco-Track dashboard.</p>
        </div>
    </header>

    <div class="container my-auto py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <!-- Error Messages Alert -->
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4 fw-bold small" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card p-4 p-md-5 border-top border-success border-4">
                    <!-- Note: You will need to create the 'password.update' route next! -->
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf

                        <!-- Hidden Token (Crucial for security) -->
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-4 text-center">
                            <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex justify-content-center align-items-center mb-3"
                                style="width: 70px; height: 70px;">
                                <span class="fs-2">🔒</span>
                            </div>
                            <h4 class="fw-bold text-dark">Create New Password</h4>
                        </div>

                        <!-- Email Address (Read-only since it came from the link) -->
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold text-secondary">Email Address</label>
                            <input type="email" name="email" id="email"
                                class="form-control form-control-lg bg-light text-muted"
                                value="{{ $email ?? old('email') }}" readonly required>
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label small fw-bold text-secondary">New Password</label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg"
                                placeholder="Enter new password" required autofocus>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label small fw-bold text-secondary">Confirm
                                Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control form-control-lg" placeholder="Re-enter new password" required>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill shadow-sm py-3">
                            Reset Password
                        </button>
                    </form>

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
