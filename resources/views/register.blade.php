<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Eco-Track</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Centers the registration card on the screen */
        body {
            background-color: #f4f7f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-card {
            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 500px;
            /* Slightly wider than login for more fields */
            padding: 40px;
            margin: 20px;
        }

        .btn-eco {
            background: #2d6a4f;
            color: white;
            border-radius: 20px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-eco:hover {
            background: #1b4332;
            color: white;
        }

        .form-control:focus {
            border-color: #2d6a4f;
            box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.25);
        }
    </style>
</head>

<body>

    <div class="register-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-success m-0">🌿 ECO-TRACK</h3>
            <p class="text-muted small">Join the community to start earning rewards.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger small">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="registerForm" action="{{ route('register.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="firstName" class="form-label fw-bold small">First Name</label>
                <input type="text" class="form-control" id="firstName" name="first_name" placeholder="Juan Dela"
                    required>
            </div>

            <div class="mb-3">
                <label for="lastName" class="form-label fw-bold small">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Cruz" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold small">Email Address</label>
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="resident@example.com" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label fw-bold small">Street / Household Address</label>
                <input type="text" class="form-control" id="address" name="address"
                    placeholder="123 Main St, Barangay..." required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="password" class="form-label fw-bold small">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••"
                        required>
                </div>
                <div class="col-md-6 mb-4">
                    <label for="confirmPassword" class="form-label fw-bold small">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="password_confirmation"
                        placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-eco w-100 py-2">Create Account</button>
        </form>

        <div class="text-center mt-4 small">
            <span class="text-muted">Already have an account?</span>
            <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">Log In</a>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('homepage') }}" class="text-muted small text-decoration-none">← Back to Home</a>
        </div>
    </div>

</body>

</html>
