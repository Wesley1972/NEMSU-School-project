<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Eco-Track</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-card {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border-top: 5px solid #2d6a4f;
        }

        .brand-text {
            color: #1b4332;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .btn-eco-dark {
            background: linear-gradient(135deg, #1b4332 0%, #2d6a4f 100%);
            color: white;
            border-radius: 25px;
            font-weight: bold;
            border: none;
            transition: all 0.3s;
        }

        .btn-eco-dark:hover {
            background: linear-gradient(135deg, #143325 0%, #1b4332 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(27, 67, 50, 0.2);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2d6a4f;
            box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.25);
        }

        .nav-pills .nav-link {
            color: #6c757d;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .nav-pills .nav-link.active {
            background-color: #e9f5ec;
            color: #2d6a4f;
        }

        a {
            text-decoration-line: none;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <a href="{{ route('homepage') }}">
            <h3 class="brand-text m-0">🌿 ECO-TRACK</h3>
            </a>
            <p class="text-muted small mt-2">Sign in to manage your waste smarter.</p>
        </div>

        <ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded-pill" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active w-100" id="resident-tab" data-bs-toggle="pill"
                    data-bs-target="#resident-login" type="button" role="tab" aria-controls="resident-login"
                    aria-selected="true">Resident</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100" id="operator-tab" data-bs-toggle="pill" data-bs-target="#operator-login"
                    type="button" role="tab" aria-controls="operator-login" aria-selected="false">Operator</button>
            </li>
        </ul>

        @if ($errors->any())
            <div class="alert alert-danger small text-start">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade show active" id="resident-login" role="tabpanel" aria-labelledby="resident-tab">
                <form id="residentLoginForm" action="{{ route('login.authenticate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role" value="resident">

                    <div class="mb-3 text-start">
                        <label for="resident_email" class="form-label fw-bold small text-secondary">Email
                            Address</label>
                        <input type="email" class="form-control" id="resident_email" name="email"
                            placeholder="name@example.com" required>
                    </div>

                    <div class="mb-4 text-start">
                        <label for="resident_password" class="form-label fw-bold small text-secondary">Password</label>
                        <input type="password" class="form-control" id="resident_password" name="password"
                            placeholder="••••••••" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 small">
                        <div class="form-check text-start">
                            <input type="checkbox" class="form-check-input" id="resident_remember" name="remember">
                            <label class="form-check-label text-muted" for="resident_remember">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}"
                            class="text-success text-decoration-none fw-bold">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-eco-dark w-100 py-2">Resident Sign In</button>
                </form>
            </div>

            <div class="tab-pane fade" id="operator-login" role="tabpanel" aria-labelledby="operator-tab">
                <form id="operatorLoginForm" action="{{ route('login.authenticate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role" value="operator">

                    <div class="mb-3 text-start">
                        <label for="operator_email" class="form-label fw-bold small text-secondary">Operator Email /
                            ID</label>
                        <input type="text" class="form-control" id="operator_email" name="email"
                            placeholder="operator@eco-track.com" required>
                    </div>

                    <div class="mb-4 text-start">
                        <label for="operator_password" class="form-label fw-bold small text-secondary">Password</label>
                        <input type="password" class="form-control" id="operator_password" name="password"
                            placeholder="••••••••" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 small">
                        <div class="form-check text-start">
                            <input type="checkbox" class="form-check-input" id="operator_remember" name="remember">
                            <label class="form-check-label text-muted" for="operator_remember">Remember me</label>
                        </div>
                        <a href="#" class="text-success text-decoration-none fw-bold">System Access Help?</a>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold rounded-pill">Operator Sign
                        In</button>
                </form>
            </div>

        </div>

        <div class="text-center mt-4 small">
            <span class="text-muted">Don't have an account?</span>
            <a href="{{ route('register') }}" class="text-success fw-bold text-decoration-none">Join the Community</a>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('homepage') }}" class="text-muted small text-decoration-none">← Back to Home</a>
            <p>Role: Operator<br>operator@gmail.com<br>Password: 1234</p>
            <p></p>
            <p></p>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
