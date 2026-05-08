<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile | Eco-Track</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, #1b4332 0%, #2d6a4f 100%);
            color: white;
            padding: 50px 0 40px;
            border-bottom: 5px solid #cddc39;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
        }

        .taxtBlack {
            color: black;
        }
    </style>
</head>

<body>

    <nav class="navbar bg-white shadow-sm py-3">
        <div class="container d-flex justify-content-between align-items-center" id="navMenu">
            <a class="navbar-brand fw-bold text-success" href="{{ route('homepage') }}">🌿 ECO-TRACK</a>
            <div>
                @auth
                    <span class="text-success me-3 fw-bold">Welcome, {{ Auth::user()->first_name }}!</span>
                    <a href="{{ route('profile') }}" class="btn btn-outline-secondary rounded-pill px-4 ms-2">Profile</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger rounded-pill px-4 ms-2">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-6 fw-bold mb-2">Resident Dashboard</h1>
            <p class="lead mx-auto mb-0" style="max-width: 700px;">Manage your account details and track your
                environmental impact.</p>
        </div>
    </header>

    <div class="container my-5">
        <div class="row g-4">

            <div class="col-md-4">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show small fw-bold" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card p-4 border-top border-success border-4 text-center mb-4">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex justify-content-center align-items-center mx-auto mb-3"
                        style="width: 80px; height: 80px;">
                        <h2 class="text-success m-0 fw-bold">{{ substr(Auth::user()->first_name, 0, 1) }}</h2>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                    <span class="badge bg-success mx-auto mb-3">Active Resident</span>

                    <div class="mt-2">
                        <a href="{{ route('password.change') }}"
                            class="btn btn-sm btn-outline-secondary rounded-pill px-4 fw-bold">Change Password</a>
                    </div>
                </div>

                <div class="card p-4 border-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-secondary m-0">Personal Info</h6>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @if ($errors->has('email') || $errors->has('address'))
                            <div class="text-danger small mb-2">
                                @foreach ($errors->all() as $error)
                                    <div>• {{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item px-0 border-0 mb-2">
                                <label class="text-muted d-block mb-1">Email Address</label>
                                <input type="email" name="email"
                                    class="form-control form-control-sm fw-bold text-dark"
                                    value="{{ old('email', Auth::user()->email) }}" required>
                            </li>

                            <li class="list-group-item px-0 border-0 mb-3">
                                <label class="text-muted d-block mb-1">Home Address</label>
                                <input type="text" name="address"
                                    class="form-control form-control-sm fw-bold text-dark"
                                    value="{{ old('address', Auth::user()->address) }}" required>
                            </li>

                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Member Since</span>
                                <span class="fw-bold text-dark">{{ Auth::user()->created_at->format('M d, Y') }}</span>
                            </li>
                        </ul>

                        <button type="submit" class="btn btn-sm btn-success w-100 rounded-pill mt-3 fw-bold">Save
                            Changes</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-4 border-top border-info border-4 mb-4">
                    <h5 class="fw-bold text-info mb-4">Your Impact Summary</h5>
                    <div class="row text-center mb-4">
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            <div class="p-3 bg-light rounded-3">
                                <h2 class="fw-bold text-info mb-0">
                                    {{ (Auth::user()->points ?? 0) + (Auth::user()->points_redeemed ?? 0) }}</h2>
                                <span class="small text-muted fw-bold">Total Earned</span>
                            </div>
                        </div>

                        <div class="col-sm-4 mb-3 mb-sm-0">
                            <div class="p-3 bg-light rounded-3">
                                <h2 class="fw-bold text-success mb-0">
                                    {{ Auth::user()->schedules()->where('status', 'completed')->count() }}</h2>
                                <span class="small text-muted fw-bold">Pickups Done</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-3">
                                <h2 class="fw-bold text-warning mb-0">
                                    {{ Auth::user()->schedules()->where('status', 'completed')->sum('weight') }}kg
                                </h2>
                                <span class="small text-muted fw-bold">Recycled</span>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold text-secondary mb-3 mt-2">Quick Actions</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('schedule') }}" class="btn btn-success rounded-pill px-4">Schedule New
                            Pickup</a>

                        <a href="{{ route('report') }}" class="btn btn-warning rounded-pill px-4 fw-bold">Report
                            Incident</a>

                        <a href="{{ route('earn') }}" class="btn btn-info rounded-pill px-4 fw-bold">Earn
                            Rewards</a>

                        <a href="{{ route('homepage') }}"
                            class="btn btn-outline-secondary rounded-pill px-4 ms-auto">Back to Home</a>
                    </div>
                </div>

                <div class="card p-4 border-top border-success border-4 mb-4">
                    <h5 class="fw-bold text-success mb-3">Your Pickup Schedules</h5>
                    <div class="list-group list-group-flush">
                        @forelse (Auth::user()->schedules()->latest()->get() as $schedule)
                            <div
                                class="list-group-item px-0 py-3 d-flex justify-content-between align-items-start border-bottom">
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">
                                        {{ ucfirst($schedule->waste_type) }}
                                        @if ($schedule->weight)
                                            <span class="badge bg-secondary ms-1">{{ $schedule->weight }} kg</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted d-block">📍 {{ $schedule->pickup_address }}
                                        ({{ ucfirst($schedule->location_type) }})
                                    </small>
                                </div>
                                <div class="text-end">
                                    @if ($schedule->status === 'completed')
                                        <span class="badge bg-success rounded-pill mb-1">✓ Completed</span>
                                    @else
                                        <span class="badge bg-warning text-dark rounded-pill mb-1">Pending</span>
                                    @endif
                                    <span
                                        class="d-block small text-muted">{{ $schedule->created_at->format('M d, Y') }}</span>
                                    <span class="small text-muted">{{ $schedule->created_at->format('h:i A') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 bg-light rounded">
                                <p class="text-muted mb-0 small">You haven't scheduled any pickups yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card p-4 border-top border-warning border-4 mb-4">
                    <h5 class="fw-bold text-warning mb-3">Your Incident Reports</h5>
                    <div class="list-group list-group-flush">
                        @forelse (Auth::user()->reports()->latest()->get() as $report)
                            <div
                                class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom">
                                <div style="flex: 1;">
                                    <h6 class="mb-1 fw-bold text-dark">
                                        {{ ucwords(str_replace('_', ' ', $report->incident_type)) }}
                                        @if ($report->is_hazardous)
                                            <span class="badge bg-danger ms-2">⚠️ Hazardous</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted d-block">📍 {{ $report->location }}</small>
                                    @if ($report->notes)
                                        <small
                                            class="text-muted d-block mt-1 fst-italic">"{{ Str::limit($report->notes, 40) }}"</small>
                                    @endif
                                </div>

                                <div class="px-3 text-center" style="width: 100px;">
                                    @if ($report->photo_path)
                                        <a href="{{ asset('storage/' . $report->photo_path) }}" target="_blank"
                                            title="Click to enlarge">
                                            <div class="rounded border shadow-sm bg-light d-flex align-items-center justify-content-center mx-auto"
                                                style="width: 50px; height: 50px; overflow: hidden; transition: transform 0.2s;">
                                                <img src="{{ asset('storage/' . $report->photo_path) }}"
                                                    alt="Reported Photo"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                            <small class="fw-bold text-primary" style="font-size: 0.7rem;">View
                                                Photo</small>
                                        </a>
                                    @else
                                        <span class="text-muted small fst-italic" style="font-size: 0.7rem;">No
                                            Image</span>
                                    @endif
                                </div>

                                <div class="text-end" style="min-width: 110px;">
                                    @if ($report->status === 'resolved')
                                        <span class="badge bg-success rounded-pill mb-1">✓ Resolved</span>
                                    @else
                                        <span class="badge bg-warning text-dark rounded-pill mb-1">In Review</span>
                                    @endif
                                    <span class="d-block small text-muted"
                                        style="font-size: 0.75rem;">{{ $report->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 bg-light rounded">
                                <p class="text-muted mb-0 small">You haven't reported any incidents yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
