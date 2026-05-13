<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Rewards | Eco-Track</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .header-green {
            background: #2d6a4f;
            color: white;
            padding: 40px 0;
        }

        .points-badge {
            background: #ff9f1c;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: bold;
            display: inline-block;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
        }

        tbody tr:nth-child(odd) {
            background-color: #f2f9f6;
        }

        .redeem-input {
            text-align: center;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 8px;
            font-weight: bold;
            color: #2d6a4f;
            width: 100%;
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .redeem-input:focus {
            outline: none;
            border-color: #2d6a4f;
            box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.25);
        }

        .impact-box {
            background-color: rgba(150, 150, 150, 0.1);
            border-radius: 12px;
            padding: 20px 10px;
            height: 100%;
            transition: transform 0.2s ease;
        }

        .impact-box:hover {
            transform: translateY(-3px);
        }
    </style>
</head>

<body>

    <header class="header-green text-center">
        <div class="container">
            <h1>Green Rewards</h1>
            <p>Turning your waste into worth.</p>
            <a href="{{ route('homepage') }}" class="text-white">← Back to Dashboard</a>
        </div>
    </header>

    <div class="container my-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-4 text-center h-100">
                    <h5>Total Balance</h5>
                    <div id="total-balance" class="display-4 fw-bold text-success my-3">
                        {{ number_format(Auth::user()->points) }}
                    </div>
                    <p class="text-muted mb-2">Available Eco-Points</p>

                    <input type="number" id="redeem-amount" class="redeem-input" placeholder="Points to redeem"
                        min="1">

                    <div class="mb-3 mt-1 text-start px-1">
                        <label class="form-label text-muted small fw-bold mb-2">Payment Option:</label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="payment_method" id="pay_cash" value="Cash"
                                autocomplete="off" checked>
                            <label class="btn btn-outline-success flex-fill rounded-pill py-1"
                                for="pay_cash">Cash</label>

                            <input type="radio" class="btn-check" name="payment_method" id="pay_gcash" value="GCash"
                                autocomplete="off">
                            <label class="btn btn-outline-primary flex-fill rounded-pill py-1"
                                for="pay_gcash">GCash</label>
                        </div>
                    </div>

                    <small id="error-msg" class="text-danger d-none mb-2 d-block">Invalid amount</small>

                    <button id="redeem-btn" class="btn btn-eco w-100 mt-2 fw-bold"
                        style="background: #ff9f1c; color: black !important; border-radius: 20px;">Redeem Custom
                        Amount</button>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-4 h-100">
                    <h5 class="mb-4">Environmental Impact</h5>
                    <div class="row text-center g-3">
                        <div class="col-sm-4">
                            <div class="impact-box">
                                <h2 class="fw-bold text-info mb-0">
                                    {{ Auth::user()->schedules()->where('status', 'completed')->where('waste_type', 'plastic')->sum('weight') ?? 0 }}kg
                                </h2>
                                <span class="small text-muted fw-bold">Plastic Recycled</span>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="impact-box">
                                <h2 class="fw-bold text-success mb-0">
                                    {{ Auth::user()->schedules()->where('status', 'completed')->count() }}
                                </h2>
                                <span class="small text-muted fw-bold">Pickups Done</span>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="impact-box">
                                <h2 class="fw-bold text-warning mb-0">
                                    {{ Auth::user()->schedules()->where('status', 'completed')->where('waste_type', 'metal')->sum('weight') ?? 0 }}kg
                                </h2>
                                <span class="small text-muted fw-bold">Metal Recycled</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card p-4">
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
                                @forelse($redemptions ?? [] as $redemption)
                                    <tr>
                                        <td>{{ $redemption->created_at->format('M d, Y') }}</td>
                                        <td class="text-danger fw-bold">-{{ number_format($redemption->amount) }}</td>

                                        <td class="text-success fw-bold">
                                            @if (!empty($redemption->custom_reward))
                                                <span class="badge bg-info border border-info shadow-sm mb-1"
                                                    style="color: black !important;">Custom Reward</span><br>
                                            @else
                                                ₱{{ number_format(($redemption->amount / $pointsAmount) * $pesoEquivalent, 2) }}
                                            @endif

                                        </td>

                                        <td>
                                            @if (!empty($redemption->custom_reward))
                                                <span class="badge bg-info border border-info shadow-sm mb-1"
                                                    style="color: black !important;">Custom Reward</span><br>
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

                                        <td>
                                            @if ($redemption->status === 'completed')
                                                <span class="badge bg-success"
                                                    style="color: black !important;">Completed</span>
                                            @else
                                                <span class="badge bg-warning"
                                                    style="color: black !important;">Pending</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($redemption->status === 'completed')
                                                <button class="btn btn-sm btn-secondary" disabled>Confirmed</button>
                                            @else
                                                <button class="btn btn-sm btn-outline-danger cancel-btn"
                                                    data-id="{{ $redemption->id }}">Cancel</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty-row">
                                        <td colspan="5" class="text-center text-muted py-4">You haven't redeemed any
                                            points yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card p-4">
                    <h5 class="mb-4">Earning History</h5>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Waste Type</th>
                                    <th>Weight</th>
                                    <th>Points Gained</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->created_at->format('M d, Y') }}</td>
                                        <td>{{ ucfirst($schedule->waste_type) }}</td>
                                        <td>{{ $schedule->weight ? $schedule->weight . ' kg' : 'TBD' }}</td>
                                        <td
                                            class="{{ $schedule->status === 'completed' ? 'text-success fw-bold' : 'text-muted fw-bold' }}">
                                            {{ $schedule->status === 'completed' ? '+' . $schedule->points_earned : 'Pending' }}
                                        </td>
                                        <td>
                                            @if ($schedule->status === 'completed')
                                                <span class="badge bg-success"
                                                    style="color: black !important;">Verified</span>
                                            @else
                                                <span class="badge bg-warning"
                                                    style="color: black !important;">Unverified</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">You haven't scheduled any
                                            pickups yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <!-- Incident Report History Table -->
            <div class="col-12 mt-2">
                <div class="card p-4">
                    <h5 class="mb-4">Incident Report History</h5>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Incident Type</th>
                                    <th>Location</th>
                                    <th>Points Gained</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports ?? [] as $report)
                                    <tr>
                                        <td>{{ $report->created_at->format('M d, Y') }}</td>

                                        <!-- Formats 'illegal_dumping' to 'Illegal Dumping' -->
                                        <td class="text-capitalize">
                                            {{ str_replace('_', ' ', $report->incident_type) }}
                                        </td>

                                        <td>{{ Str::limit($report->location, 35) }}</td>

                                        <!-- Dynamically shows points based on status -->
                                        <td
                                            class="{{ $report->status === 'resolved' ? 'text-success fw-bold' : 'text-muted fw-bold' }}">
                                            {{ $report->status === 'resolved' ? '+' . ($reportRate ?? 50) : 'Pending' }}
                                        </td>

                                        <td>
                                            @if ($report->status === 'resolved')
                                                <span class="badge bg-success"
                                                    style="color: black !important;">Resolved</span>
                                            @else
                                                <span class="badge bg-warning" style="color: black !important;">Under
                                                    Review</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">You haven't submitted
                                            any incident reports yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
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
            balanceDisplay.innerText = currentBalance.toLocaleString();
            redeemInput.max = currentBalance;

            if (currentBalance <= 0) {
                disableRedemptionUI();
            }

            // REDEEM POINTS LOGIC
            redeemBtn.addEventListener('click', function(e) {
                e.preventDefault();

                const amountToRedeem = parseInt(redeemInput.value);
                const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked')
                    .value;

                // Hardcoded dummy phone number
                const userPhone = "0912 345 6789";

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
                            // Update the balance display
                            currentBalance = data.new_balance;
                            balanceDisplay.innerText = currentBalance.toLocaleString();

                            // Dynamically add the new row to the Redemption History table
                            const redemptionTableBody = document.querySelector(
                                '#redemption-table tbody');

                            // Remove empty message if it exists
                            const emptyMessage = redemptionTableBody.querySelector('.empty-row');
                            if (emptyMessage) {
                                emptyMessage.remove();
                            }

                            const today = new Date().toLocaleDateString('en-US', {
                                month: 'short',
                                day: '2-digit',
                                year: 'numeric'
                            });

                            // Check if GCash is selected, append hardcoded number if true
                            let paymentDisplayText = selectedPaymentMethod;
                            if (selectedPaymentMethod.toLowerCase() === 'gcash') {
                                paymentDisplayText = `GCash (${userPhone})`;
                            }

                            const newRowHTML = `
    <tr>
        <td>${today}</td>
        <td class="text-danger fw-bold">-${amountToRedeem.toLocaleString()}</td>
        <td class="text-success fw-bold">₱${data.cash_value}</td> <!-- NEW JS COLUMN -->
        <td class="text-capitalize">${paymentDisplayText}</td>
        <td><span class="badge bg-warning" style="color: black !important;">Pending</span></td>
        <td><button class="btn btn-sm btn-outline-danger cancel-btn" data-id="${data.redemption_id}">Cancel</button></td>
    </tr>
`;

                            redemptionTableBody.insertAdjacentHTML('afterbegin', newRowHTML);

                            redeemInput.value = '';
                            redeemInput.max = currentBalance;

                            redeemBtn.innerText = 'Success!';
                            redeemBtn.style.backgroundColor = '#28a745';

                            setTimeout(() => {
                                if (currentBalance > 0) {
                                    enableRedemptionUI(originalText);
                                } else {
                                    disableRedemptionUI();
                                }
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

            // CANCEL REDEMPTION LOGIC
            document.querySelector('#redemption-table').addEventListener('click', function(e) {
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
                                // Update balance back
                                currentBalance = data.new_balance;
                                balanceDisplay.innerText = currentBalance.toLocaleString();
                                redeemInput.max = currentBalance;

                                // Re-enable input area if it was locked
                                if (currentBalance > 0) {
                                    enableRedemptionUI('Redeem Custom Amount');
                                }

                                // Remove row visually
                                row.remove();

                                // Put empty message back if no more rows
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

            function showError(message) {
                errorMsg.innerText = message;
                errorMsg.classList.remove('d-none');
            }

            function enableRedemptionUI(originalText) {
                redeemBtn.innerText = originalText;
                redeemBtn.style.backgroundColor = '#ff9f1c';
                redeemBtn.style.color = 'black !important'; // Ensure JS enforces the style when re-enabled
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

</body>

</html>
