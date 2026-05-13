<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reward System | Eco-Track</title>
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

        .stat-card.info {
            border-left-color: #0dcaf0;
        }

        .action-btn {
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .reward-img-placeholder {
            height: 120px;
            background-color: #e9ecef;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar bg-white shadow-sm py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold text-success" href="#">🌿 ECO-TRACK <span
                    class="badge bg-success text-white ms-2 fs-6">REWARDS</span></a>
            <div>
                <span class="text-secondary me-3 fw-bold">ID: RES-2 | System</span>
                <form action="#" method="POST" class="d-inline">
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-4">Sign Out</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1">Your Rewards Portal</h2>
                <p class="mb-0 text-light opacity-75">Convert your eco-efforts into real value.</p>
            </div>

            <!-- Grouped Settings Panels -->
            <div class="d-flex flex-wrap gap-3 align-items-center">

            </div>
        </div>
    </header>

    <!-- Resident Filter & Reward History Section -->
    <div class="container mt-4">
        <div class="d-flex flex-wrap gap-3 align-items-stretch">

            <!-- Static Dummy Data for Selected Resident -->
            <div class="card p-3 shadow-sm border-0 flex-grow-1 bg-white"
                style="border-radius: 12px; border-left: 5px solid #2d6a4f !important;">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-success mb-0">
                        Resident Summary:
                        @if (isset($selectedUser))
                            {{ $selectedUser->first_name }} {{ $selectedUser->last_name }}
                        @else
                            No Resident Selected
                        @endif
                    </h6>
                </div>

                <div class="row g-2 text-center">
                    <div class="col-md-2 col-4">
                        <div class="p-2 bg-light rounded border h-100">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.65rem;">Plastic
                                (Kg)</small>
                            <span class="fw-bold text-info fs-5">
                                {{ isset($selectedUser) ? $selectedUser->schedules()->where('status', 'completed')->where('waste_type', 'plastic')->sum('weight') ?? 0 : 0 }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-2 col-4">
                        <div class="p-2 bg-light rounded border h-100">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.65rem;">Metal
                                (Kg)</small>
                            <span class="fw-bold text-secondary fs-5">
                                {{ isset($selectedUser) ? $selectedUser->schedules()->where('status', 'completed')->where('waste_type', 'metal')->sum('weight') ?? 0 : 0 }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-2 col-4">
                        <div class="p-2 bg-light rounded border h-100">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.65rem;">Total
                                Pickups</small>
                            <span class="fw-bold text-dark fs-5">
                                {{ isset($selectedUser) ? $selectedUser->schedules()->where('status', 'completed')->count() : 0 }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-2 col-4">
                        <div class="p-2 bg-success bg-opacity-10 rounded border border-success border-opacity-25 h-100">
                            <small class="text-success d-block fw-bold text-uppercase" style="font-size: 0.65rem;">Total
                                Earned</small>
                            <span class="fw-bold text-success fs-5">
                                {{ isset($selectedUser) ? ($selectedUser->points ?? 0) + ($selectedUser->points_redeemed ?? 0) : 0 }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-2 col-4">
                        <div class="p-2 bg-warning bg-opacity-10 rounded border border-warning border-opacity-25 h-100">
                            <small class="text-warning d-block fw-bold text-uppercase"
                                style="font-size: 0.65rem;">Redeemed</small>
                            <span class="fw-bold text-warning fs-5">
                                {{ isset($selectedUser) ? $selectedUser->points_redeemed ?? 0 : 0 }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-2 col-4">
                        <div class="p-2 bg-primary bg-opacity-10 rounded border border-primary border-opacity-25 h-100">
                            <small class="text-primary d-block fw-bold text-uppercase"
                                style="font-size: 0.65rem;">Balance Left</small>
                            <span class="fw-bold text-primary fs-5">
                                {{ isset($selectedUser) ? $selectedUser->points ?? 0 : 0 }}
                            </span>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div><br>

    <!-- Redemption History Section (Dynamically Populated) -->
    <div class="container mt-4 mb-5">
        <div class="card p-4 shadow-sm border-0">
            <h5 class="mb-4">Redemption History</h5>

            <div class="table-responsive">
                <table class="table align-middle" id="redemption-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Points Redeemed</th>
                            <th>Cash Value</th>
                            <th>Payment Option</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($selectedUser) && $selectedUser->redemptions()->count() > 0)
                            @foreach ($selectedUser->redemptions()->latest()->get() as $redemption)
                                <tr>
                                    <td>{{ $redemption->created_at->format('M d, Y') }}</td>
                                    <td class="text-danger fw-bold">-{{ number_format($redemption->amount) }}</td>

                                    <td class="text-success fw-bold">
                                        @if (!empty($redemption->custom_reward))
                                            <span
                                                class="badge bg-info text-dark border border-info shadow-sm mb-1">Custom
                                                Reward</span><br>
                                        @else
                                            ₱{{ number_format(($redemption->amount / $pointsAmount) * $pesoEquivalent, 2) }}
                                        @endif
                                    </td>

                                    <td>
                                        @if (!empty($redemption->custom_reward))
                                            <span
                                                class="badge bg-info text-dark border border-info shadow-sm mb-1">Custom
                                                Reward</span><br>
                                            <span class="fw-bold text-dark">{{ $redemption->custom_reward }}</span>
                                        @else
                                            @if (strtolower($redemption->payment_method) === 'gcash')
                                                GCash (0912 345 6789)
                                            @else
                                                <span
                                                    class="text-capitalize">{{ $redemption->payment_method ?? 'Cash' }}</span>
                                            @endif
                                        @endif
                                    </td>

                                    <!-- Dynamic Status Badge -->
                                    <td>
                                        @if ($redemption->status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($redemption->status === 'pending')
                                            @if (Auth::user()->role === 'operator')
                                                <!-- ADMIN VIEW: Default Action Buttons -->
                                                <div class="action-buttons-{{ $redemption->id }} d-flex gap-2">
                                                    <form action="{{ route('rewards.confirm', $redemption->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-success">Confirm</button>
                                                    </form>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-info toggle-custom-btn"
                                                        data-id="{{ $redemption->id }}">Custom</button>
                                                </div>

                                                <!-- ADMIN VIEW: Custom Reward Input Form (Hidden initially) -->
                                                <div class="custom-reward-form-{{ $redemption->id }} d-none">
                                                    <form action="{{ route('rewards.custom', $redemption->id) }}"
                                                        method="POST" class="d-flex gap-1 align-items-center">
                                                        @csrf
                                                        <input type="text" name="custom_reward"
                                                            class="form-control form-control-sm"
                                                            placeholder="e.g., 1kg Rice" style="width: 140px;" required>
                                                        <button type="submit"
                                                            class="btn btn-sm btn-info text-white fw-bold">Save</button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-secondary cancel-custom-btn"
                                                            data-id="{{ $redemption->id }}">X</button>
                                                    </form>
                                                </div>
                                            @else
                                                <!-- USER VIEW: Cancel Button -->
                                                <button class="btn btn-sm btn-outline-danger cancel-btn"
                                                    data-id="{{ $redemption->id }}">Cancel</button>
                                            @endif
                                        @else
                                            <!-- COMPLETED: Button disappears, shows text -->
                                            <span class="text-muted small fw-bold">Confirmed</span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr class="empty-row">
                                <td colspan="5" class="text-center text-muted py-4">
                                    {{ isset($selectedUser) ? "This user hasn't redeemed any points yet." : "You haven't redeemed any points yet." }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const balanceDisplay = document.getElementById('total-balance');
            const redeemBtn = document.getElementById('redeem-btn');
            const redeemInput = document.getElementById('redeem-amount');
            const errorMsg = document.getElementById('error-msg');

            let currentBalance = {{ Auth::user()->points ?? 0 }};
            if (balanceDisplay) balanceDisplay.innerText = currentBalance.toLocaleString();
            if (redeemInput) redeemInput.max = currentBalance;

            if (currentBalance <= 0 && redeemBtn) disableRedemptionUI();












            // Toggle logic for the Custom Reward field
            document.querySelectorAll('.toggle-custom-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    document.querySelector(`.action-buttons-${id}`).classList.add('d-none');
                    document.querySelector(`.custom-reward-form-${id}`).classList.remove('d-none');
                });
            });

            // Toggle logic to close the Custom Reward field
            document.querySelectorAll('.cancel-custom-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    document.querySelector(`.custom-reward-form-${id}`).classList.add('d-none');
                    document.querySelector(`.action-buttons-${id}`).classList.remove('d-none');
                });
            });

            if (redeemBtn) {
                redeemBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const amountToRedeem = parseInt(redeemInput.value);
                    const selectedPaymentMethod = document.querySelector(
                        'input[name="payment_method"]:checked').value;

                    if (isNaN(amountToRedeem) || amountToRedeem <= 0) {
                        showError('Please enter a valid amount.');
                        return;
                    }

                    if (amountToRedeem > currentBalance) {
                        showError('You do not have enough points.');
                        return;
                    }

                    errorMsg.classList.add('d-none');
                    const originalText = redeemBtn.innerText;
                    redeemBtn.innerText = 'Processing...';
                    redeemBtn.disabled = true;

                    fetch('{{ route('rewards.redeem') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                amount: amountToRedeem,
                                payment_method: selectedPaymentMethod
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                currentBalance = data.new_balance;
                                balanceDisplay.innerText = currentBalance.toLocaleString();

                                const redemptionTableBody = document.querySelector(
                                    '#redemption-table tbody');
                                const emptyMessage = redemptionTableBody.querySelector('.empty-row');
                                if (emptyMessage) emptyMessage.remove();

                                const today = new Date().toLocaleDateString('en-US', {
                                    month: 'short',
                                    day: '2-digit',
                                    year: 'numeric'
                                });

                                const newRowHTML = `
    <tr>
        <td>${today}</td>
        <td class="text-danger fw-bold">-${amountToRedeem.toLocaleString()}</td>
        <td class="text-success fw-bold">₱${data.cash_value}</td> <!-- NEW JS COLUMN -->
        <td class="text-capitalize">${paymentDisplayText}</td>
        <td><span class="badge bg-warning text-black">Pending</span></td>
        <td><button class="btn btn-sm btn-outline-danger cancel-btn" data-id="${data.redemption_id}">Cancel</button></td>
    </tr>
`;

                                redemptionTableBody.insertAdjacentHTML('afterbegin', newRowHTML);
                                redeemInput.value = '';
                                redeemInput.max = currentBalance;

                                redeemBtn.innerText = 'Success!';
                                redeemBtn.style.backgroundColor = '#28a745';

                                setTimeout(() => {
                                    if (currentBalance > 0) enableRedemptionUI(originalText);
                                    else disableRedemptionUI();
                                }, 1500);
                            } else {
                                showError(data.message);
                                redeemBtn.innerText = originalText;
                                redeemBtn.disabled = false;
                            }
                        })
                        .catch(error => {
                            showError('Server error. Please try again.');
                            redeemBtn.innerText = originalText;
                            redeemBtn.disabled = false;
                        });
                });
            }

            const table = document.querySelector('#redemption-table');
            if (table) {
                table.addEventListener('click', function(e) {
                    if (e.target.classList.contains('cancel-btn')) {
                        const btn = e.target;
                        const id = btn.getAttribute('data-id');
                        const row = btn.closest('tr');

                        btn.innerText = 'Canceling...';
                        btn.disabled = true;

                        fetch(`/redeem-points/cancel/${id}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    currentBalance = data.new_balance;
                                    if (balanceDisplay) balanceDisplay.innerText = currentBalance
                                        .toLocaleString();
                                    if (redeemInput) redeemInput.max = currentBalance;

                                    if (currentBalance > 0 && redeemBtn) enableRedemptionUI(
                                        'Redeem Custom Amount');

                                    row.remove();
                                    const tbody = document.querySelector('#redemption-table tbody');
                                    if (tbody.children.length === 0) {
                                        tbody.innerHTML =
                                            `<tr class="empty-row"><td colspan="5" class="text-center text-muted py-4">You haven't redeemed any points yet.</td></tr>`;
                                    }
                                } else {
                                    alert(data.message);
                                    btn.innerText = 'Cancel';
                                    btn.disabled = false;
                                }
                            })
                            .catch(error => {
                                alert('Server error while canceling.');
                                btn.innerText = 'Cancel';
                                btn.disabled = false;
                            });
                    }
                });
            }

            function showError(message) {
                errorMsg.innerText = message;
                errorMsg.classList.remove('d-none');
            }

            function enableRedemptionUI(originalText) {
                redeemBtn.innerText = originalText;
                redeemBtn.style.backgroundColor = '#ff9f1c';
                redeemBtn.disabled = false;
                redeemInput.disabled = false;
                redeemInput.placeholder = "Points to redeem";
                document.querySelectorAll('input[name="payment_method"]').forEach(radio => radio.disabled = false);
            }

            function disableRedemptionUI() {
                redeemBtn.innerText = 'No Points to Redeem';
                redeemBtn.style.backgroundColor = '#6c757d';
                redeemBtn.disabled = true;
                redeemInput.disabled = true;
                redeemInput.placeholder = "Balance empty";
                document.querySelectorAll('input[name="payment_method"]').forEach(radio => radio.disabled = true);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
