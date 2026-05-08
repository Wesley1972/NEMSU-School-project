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
                    
                    <input type="number" id="redeem-amount" class="redeem-input" placeholder="Points to redeem" min="1">
                    <small id="error-msg" class="text-danger d-none mb-2 d-block">Invalid amount</small>

                    <button id="redeem-btn" class="btn btn-eco w-100 mt-2"
                        style="background: #ff9f1c; color: white; border-radius: 20px;">Redeem Custom Amount</button>
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
                                        <td>{{ $schedule->updated_at->format('M d, Y') }}</td>
                                        <td>{{ ucfirst($schedule->waste_type) }}</td>
                                        <td>{{ $schedule->weight }} kg</td>
                                        <td class="text-success fw-bold">+{{ $schedule->points_earned }}</td>
                                        <td><span class="badge bg-success">Verified</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">You haven't completed any
                                            pickups yet. Schedule one to start earning points!</td>
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

            redeemBtn.addEventListener('click', function(e) {
                e.preventDefault(); 
                
                const amountToRedeem = parseInt(redeemInput.value);
                
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
                    body: JSON.stringify({ amount: amountToRedeem })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentBalance = data.new_balance;
                        balanceDisplay.innerText = currentBalance.toLocaleString();
                        
                        redeemInput.value = '';
                        redeemInput.max = currentBalance;
                        
                        redeemBtn.innerText = 'Success!';
                        redeemBtn.style.backgroundColor = '#28a745';
                        
                        setTimeout(() => {
                            if (currentBalance > 0) {
                                redeemBtn.innerText = originalText;
                                redeemBtn.style.backgroundColor = '#ff9f1c';
                                redeemBtn.disabled = false;
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
            
            function showError(message) {
                errorMsg.innerText = message;
                errorMsg.classList.remove('d-none');
            }
            
            function disableRedemptionUI() {
                redeemBtn.innerText = 'No Points to Redeem';
                redeemBtn.style.backgroundColor = '#6c757d';
                redeemBtn.disabled = true;
                redeemInput.disabled = true;
                redeemInput.placeholder = "Balance empty";
            }
        });
    </script>
</body>

</html>