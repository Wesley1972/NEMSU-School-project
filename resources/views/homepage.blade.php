<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco-Track | Smart Waste & Recycling</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, #1b4332 0%, #2d6a4f 100%);
            color: white;
            padding: 80px 0 60px;
            border-bottom: 5px solid #cddc39;
        }

        .btn-eco {
            background: #cddc39;
            color: black;
            border-radius: 25px;
            font-weight: bold;
            padding: 10px 30px;
            transition: all 0.3s;
        }

        .btn-eco:hover {
            background: #d4e157;
            transform: translateY(-2px);
        }

        .objective-badge {
            background: #e9f5ec;
            color: #2d6a4f;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            margin: 5px;
            display: inline-block;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
        }

        a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>

<body>

    <nav class="navbar bg-white shadow-sm py-3">
        <div class="container d-flex justify-content-between align-items-center" id="navMenu">
            <a class="navbar-brand fw-bold text-success" href="#">🌿 ECO-TRACK</a>
            <div>
                @auth
                    <span class="text-success me-3 fw-bold">Welcome, {{ Auth::user()->first_name }}!</span>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger rounded-pill px-4">Logout</button>
                    </form>
                @endauth

                @guest
                    <span class="text-muted me-3 fw-bold">Group 5</span>
                    <a href="{{ route('login') }}" class="btn btn-outline-success rounded-pill px-4">Login</a>
                @endguest

                <a href="{{ route('profile') }}" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Profile</a>
            </div>
        </div>
    </nav>

    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-5 fw-bold mb-3">Smart Waste & Recycling Management</h1>
            <p class="lead mx-auto mb-4" style="max-width: 700px;">
                A practical, community-first platform that reduces waste, prevents illegal disposal, and turns recycling
                into a
                local benefit through measurable incentives.
            </p>
            <a href="{{ route('register') }}" class="btn btn-eco shadow">Join the Community</a>
        </div>
    </header>

    <div class="container text-center my-5">
        <h4 class="text-muted mb-4">Our Core Objectives</h4>
        <div class="d-flex justify-content-center flex-wrap">
            <span class="objective-badge">✓ Reduce Illegal Dumping</span>
            <span class="objective-badge">✓ Increase Recycling Rates</span>
            <span class="objective-badge">✓ Improve Service Efficiency</span>
            <span class="objective-badge">✓ Foster Local Benefits</span>
        </div>
    </div>

    <div class="container my-5">
        <div class="row g-4 text-center">

            <div class="col-md-4">
                <a href="{{ route('schedule') }}">
                    <div class="card p-4 border-top border-success border-4">
                        <h1 class="mb-3">🚛</h1>
                        <h5 class="fw-bold text-success">Scheduled Pickups</h5>
                        <p class="small text-muted mb-3">Smart scheduling and route optimization for organic,
                            recyclable, and
                            e-waste materials.</p>
                        <span class="text-success small fw-bold">Schedule Now →</span>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('report') }}">
                    <div class="card p-4 border-top border-warning border-4">
                        <h1 class="mb-3">📍</h1>
                        <h5 class="fw-bold text-warning" style="color: #ff9f1c !important;">Incident Reporting</h5>
                        <p class="small text-muted mb-3">Submit photo and geo-tagged reports for immediate priority
                            triage by
                            municipal teams.</p>
                        <span class="small fw-bold" style="color: #ff9f1c;">Report Issue →</span>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('earn') }}">
                    <div class="card p-4 border-top border-info border-4">
                        <h1 class="mb-3">🌱</h1>
                        <h5 class="fw-bold text-info">Eco-Points Rewards</h5>
                        <p class="small text-muted mb-3">Earn points for verified recycling and redeem local vouchers at
                            our Rewards
                            Marketplace.</p>
                        <span class="text-info small fw-bold">View Ledger →</span>
                    </div>
                </a>
            </div>

        </div>
    </div>

</body>

</html>
