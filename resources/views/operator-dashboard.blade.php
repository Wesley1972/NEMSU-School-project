<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Dashboard | Eco-Track</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, #143325 0%, #1b4332 100%);
            color: white;
            padding: 40px 0 30px;
            border-bottom: 5px solid #ff9f1c;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            border-left: 5px solid #2d6a4f;
        }

        .stat-card.warning {
            border-left-color: #ff9f1c;
        }

        .stat-card.danger {
            border-left-color: #dc3545;
        }

        .action-btn {
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <nav class="navbar bg-white shadow-sm py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold text-success" href="#">🌿 ECO-TRACK <span
                    class="badge bg-warning text-dark ms-2 fs-6">OPERATOR</span></a>
            <div>
                @auth
                    <span class="text-secondary me-3 fw-bold">ID: OP-{{ Auth::user()->id ?? '8492' }} |
                        {{ Auth::user()->first_name ?? 'Operator' }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-4">Sign Out</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1">Dispatch & Routing</h2>
                <p class="mb-0 text-light opacity-75">Today's Date: {{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
            </div>

            <!-- Reward Rates Configuration Panel -->
            <div class="bg-dark bg-opacity-25 p-2 px-3 rounded-3 border border-light border-opacity-10 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="fw-bold text-warning text-uppercase"
                        style="font-size: 0.7rem; letter-spacing: 1px;">⚙️ Reward Rates (Per Kg)</small>
                </div>

                <form action="{{ route('operator.rates') }}" method="POST" class="d-flex align-items-center gap-2">
                    @csrf
                    <div class="input-group input-group-sm" style="width: 140px;">
                        <span class="input-group-text bg-info text-dark fw-bold border-0"
                            style="font-size: 0.8rem;">Plastic</span>
                        <input type="number" name="plastic_rate" class="form-control border-0 text-center fw-bold"
                            value="{{ $plasticRate }}" min="0" step="1">
                        <span class="input-group-text bg-white text-muted border-0"
                            style="font-size: 0.7rem;">pts</span>
                    </div>

                    <div class="input-group input-group-sm" style="width: 140px;">
                        <span class="input-group-text bg-secondary text-white fw-bold border-0"
                            style="font-size: 0.8rem;">Metal</span>
                        <input type="number" name="metal_rate" class="form-control border-0 text-center fw-bold"
                            value="{{ $metalRate }}" min="0" step="1">
                        <span class="input-group-text bg-white text-muted border-0"
                            style="font-size: 0.7rem;">pts</span>
                    </div>

                    <button type="submit" class="btn btn-sm btn-warning fw-bold rounded-pill px-3 shadow-sm"
                        style="font-size: 0.8rem;">Save</button>
                </form>
            </div>

        </div>
    </header>

    <div class="container mt-4">
        <div class="d-flex flex-wrap gap-3 align-items-stretch">

            <div class="card p-3 shadow-sm border-0"
                style="background-color: #1a1a1a; border-radius: 12px; width: fit-content;">
                <form action="{{ route('operator.dashboard') }}" method="GET" id="filterForm"
                    class="row g-3 align-items-end h-100">

                    <div class="col-auto">
                        <label for="user_id" class="form-label fw-bold text-light mb-1"
                            style="font-size: 0.85rem; opacity: 0.8;">Filter by Resident</label>
                        <select name="user_id" id="user_id"
                            class="form-select bg-dark text-light border-secondary shadow-sm"
                            style="width: auto; min-width: 250px; border-color: #333 !important;"
                            onchange="document.getElementById('filterForm').submit();">
                            <option value="">--- All Residents ---</option>
                            @foreach ($residents as $resident)
                                <option value="{{ $resident->id }}"
                                    {{ request('user_id') == $resident->id ? 'selected' : '' }}>
                                    {{ $resident->first_name }} {{ $resident->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="view" value="{{ request('view', 'pending') }}">
                    </div>

                    <div class="col-auto">
                        @if (request('view') == 'completed')
                            <a href="{{ route('operator.dashboard', array_merge(request()->query(), ['view' => 'pending'])) }}"
                                class="btn btn-success rounded-pill px-4 shadow-sm fw-bold">
                                🟢 View Active Tasks
                            </a>
                        @else
                            <a href="{{ route('operator.dashboard', array_merge(request()->query(), ['view' => 'completed'])) }}"
                                class="btn btn-outline-warning rounded-pill px-4 shadow-sm fw-bold">
                                📜 View History
                            </a>
                        @endif
                    </div>

                    @if (request('user_id') || request('view'))
                        <div class="col-auto">
                            <a href="{{ route('operator.dashboard') }}"
                                class="btn btn-link text-secondary text-decoration-none p-0 pb-2 ms-2">
                                Reset All
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            @if (request('user_id') && isset($selectedUser))
                <div class="card p-3 shadow-sm border-0 flex-grow-1 bg-white"
                    style="border-radius: 12px; border-left: 5px solid #2d6a4f !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold text-success mb-0">Resident Summary: {{ $selectedUser->first_name }}
                            {{ $selectedUser->last_name }}</h6>
                    </div>
                    <div class="row g-2 text-center">

                        <div class="col-md-2 col-4">
                            <div class="p-2 bg-light rounded border h-100">
                                <small class="text-muted d-block fw-bold text-uppercase"
                                    style="font-size: 0.65rem;">Plastic (Kg)</small>
                                <span
                                    class="fw-bold text-info fs-5">{{ $selectedUser->schedules()->where('status', 'completed')->where('waste_type', 'plastic')->sum('weight') ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-md-2 col-4">
                            <div class="p-2 bg-light rounded border h-100">
                                <small class="text-muted d-block fw-bold text-uppercase"
                                    style="font-size: 0.65rem;">Metal (Kg)</small>
                                <span
                                    class="fw-bold text-secondary fs-5">{{ $selectedUser->schedules()->where('status', 'completed')->where('waste_type', 'metal')->sum('weight') ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-md-2 col-4">
                            <div class="p-2 bg-light rounded border h-100">
                                <small class="text-muted d-block fw-bold text-uppercase"
                                    style="font-size: 0.65rem;">Total Pickups</small>
                                <span
                                    class="fw-bold text-dark fs-5">{{ $selectedUser->schedules()->where('status', 'completed')->count() }}</span>
                            </div>
                        </div>

                        <div class="col-md-2 col-4">
                            <div
                                class="p-2 bg-success bg-opacity-10 rounded border border-success border-opacity-25 h-100">
                                <small class="text-success d-block fw-bold text-uppercase"
                                    style="font-size: 0.65rem;">Total Earned</small>
                                <span
                                    class="fw-bold text-success fs-5">{{ ($selectedUser->points ?? 0) + ($selectedUser->points_redeemed ?? 0) }}</span>
                            </div>
                        </div>

                        <div class="col-md-2 col-4">
                            <div
                                class="p-2 bg-warning bg-opacity-10 rounded border border-warning border-opacity-25 h-100">
                                <small class="text-warning d-block fw-bold text-uppercase"
                                    style="font-size: 0.65rem;">Redeemed</small>
                                <span
                                    class="fw-bold text-warning fs-5">{{ $selectedUser->points_redeemed ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-md-2 col-4">
                            <div
                                class="p-2 bg-primary bg-opacity-10 rounded border border-primary border-opacity-25 h-100">
                                <small class="text-primary d-block fw-bold text-uppercase"
                                    style="font-size: 0.65rem;">Balance Left</small>
                                <span class="fw-bold text-primary fs-5">{{ $selectedUser->points ?? 0 }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            @endif

        </div>
    </div>

    <div class="container my-4">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stat-card p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center">



                        <div>
                            <h6 class="text-muted mb-1 fw-bold">
                                {{ request('view') == 'completed' ? 'Completed Pickups' : 'Pending Pickups' }}
                            </h6>
                            <h3 class="fw-bold text-success mb-0">{{ $schedules->count() }}</h3>
                        </div>




                        <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success fs-4">🚛</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card warning p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-bold">
                                {{ request('view') == 'completed' ? 'Resolved Reports' : 'Active Reports' }}
                            </h6>
                            <h3 class="fw-bold text-warning mb-0">{{ $reports->count() }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning fs-4">📋</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card stat-card danger p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-bold">
                                {{ request('view') == 'completed' ? 'Resolved Hazards' : 'Hazards / Urgent' }}
                            </h6>
                            <h3 class="fw-bold text-danger mb-0">{{ $reports->where('is_hazardous', true)->count() }}
                            </h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger fs-4">⚠️</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-lg-7">
                <div class="card card-hover h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-success m-0">Completed Pickups</h5>
                        <span class="badge bg-secondary">Latest Requests</span>
                    </div>

                    <div class="list-group list-group-flush">

                        @forelse($schedules as $schedule)
                            <div class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span
                                            class="badge bg-info text-dark mb-2">{{ ucfirst($schedule->waste_type) }}</span>
                                        <span class="badge border border-success text-success mb-2 ms-1">
                                            +{{ ($schedule->weight ?? 0) * (strtolower($schedule->waste_type) === 'plastic' ? $plasticRate : $metalRate) }}
                                            pts
                                        </span>
                                        <h6 class="fw-bold text-dark mb-1">{{ $schedule->pickup_address }}
                                            ({{ ucfirst($schedule->location_type) }})
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            Resident: {{ $schedule->user->first_name ?? 'Unknown' }}
                                            {{ $schedule->user->last_name ?? '' }}

                                            @if ($schedule->weight)
                                                • Est: {{ $schedule->weight }}kg
                                            @endif
                                        </p>
                                    </div>

                                    <div class="text-end">
                                        @if (request('view') == 'completed')
                                            <button class="btn btn-sm btn-secondary action-btn px-3 text-nowrap"
                                                style="opacity: 0.6; cursor: not-allowed;" disabled>
                                                ✅ Completed
                                            </button>
                                        @else
                                            <form action="{{ route('schedule.complete', $schedule->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-success action-btn px-3 text-nowrap">Mark
                                                    Done</button>
                                            </form>
                                        @endif
                                        <div class="small text-muted mt-2 text-nowrap">
                                            {{ $schedule->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 bg-light rounded mt-2">
                                <p class="text-muted mb-0 fw-bold">No active pickup requests.</p>
                            </div>
                        @endforelse

                    </div>

                    @if ($schedules->count() > 0)
                        <button class="btn btn-outline-success w-100 mt-3 fw-bold">Load More Routes</button>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card card-hover h-100 p-4 border-top border-warning border-4">
                    <h5 class="fw-bold text-warning mb-4">Urgent Incident Reports</h5>

                    <div class="list-group list-group-flush">
                        @forelse($reports as $report)
                            <div
                                class="list-group-item p-3 bg-light rounded-3 mb-3 border-start {{ $report->is_hazardous ? 'border-danger' : 'border-info' }} border-4 d-flex justify-content-between align-items-center">

                                <div style="flex: 1;">
                                    <div class="mb-2">
                                        @if ($report->is_hazardous)
                                            <span class="badge bg-danger rounded-pill">⚠️ Hazardous</span>
                                        @else
                                            <span
                                                class="badge bg-info text-dark rounded-pill">{{ ucwords(str_replace('_', ' ', $report->incident_type)) }}</span>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">{{ $report->user->first_name ?? 'Resident' }}
                                        reported:</h6>
                                    <p class="small text-secondary mb-1">📍 {{ $report->location }}</p>
                                    @if ($report->notes)
                                        <p class="small text-muted fst-italic mb-0">
                                            "{{ Str::limit($report->notes, 40) }}"</p>
                                    @endif
                                </div>

                                <div class="px-3 text-center" style="width: 100px;">
                                    @if ($report->photo_path)
                                        <a href="{{ asset('storage/' . $report->photo_path) }}" target="_blank"
                                            title="Click to enlarge" class="text-decoration-none">
                                            <div class="rounded border shadow-sm bg-white d-flex align-items-center justify-content-center mx-auto mb-1"
                                                style="width: 50px; height: 50px; overflow: hidden; transition: transform 0.2s;">



                                                <img src="{{ asset('storage/' . $report->photo_path) }}"
                                                    alt="Report Photo"
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

                                <div class="text-end d-flex flex-column justify-content-between"
                                    style="min-width: 110px; height: 100%;">
                                    <div class="mb-2">
                                        <span
                                            class="small text-muted fw-bold">{{ $report->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div>
                                        @if (request('view') == 'completed')
                                            <button class="btn btn-sm btn-secondary action-btn w-100"
                                                style="opacity: 0.6; cursor: not-allowed;" disabled>
                                                Resolved
                                            </button>
                                        @else
                                            <form action="{{ route('report.resolve', $report->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm {{ $report->is_hazardous ? 'btn-danger' : 'btn-success' }} action-btn w-100">
                                                    {{ $report->is_hazardous ? 'Dispatch Crew' : 'Resolve' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        @empty
                            <div class="text-center py-5 bg-white rounded border">
                                <p class="text-muted mb-0 fw-bold">No pending incident reports.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
