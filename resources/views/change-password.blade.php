<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password | Eco-Track</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .password-card {
            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            margin: 20px;
            border-top: 5px solid #2d6a4f;
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

    <div class="password-card">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-success m-0">Security Settings</h4>
            <p class="text-muted small">Update your Eco-Track password.</p>
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

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="current_password" class="form-label fw-bold small">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold small">New Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-bold small">Confirm New Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    required>
            </div>

            <button type="submit" class="btn btn-eco w-100 py-2">Update Password</button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('profile') }}" class="text-muted small text-decoration-none">← Back to Profile</a>
        </div>
    </div>

</body>

</html>
