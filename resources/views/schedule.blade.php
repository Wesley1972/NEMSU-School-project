<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Pickup | Eco-Track</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .header-green {
            background: #2d6a4f;
            color: white;
            padding: 40px 0;
        }

        .form-container {
            margin-top: -30px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        fieldset {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        legend {
            width: auto;
            padding: 0 10px;
            font-size: 1.1rem;
            font-weight: bold;
            color: #2d6a4f;
        }
    </style>
</head>

<body>

    <header class="header-green text-center">
        <div class="container">
            <h1>Schedule a Waste Pickup</h1>
            <p>Fill out the details below to help us manage your waste.</p>
            <a href="{{ route('homepage') }}" class="text-white">← Back to Dashboard</a>
        </div>
    </header>

    <div class="container form-container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4">
                    <form id="scheduleForm" action="{{ route('schedule.store') }}" method="POST">
                        @csrf

                        <fieldset>
                            <legend>Waste Information</legend>
                            <div class="mb-3">
                                <label for="wasteType" class="form-label">Category of Waste</label>
                                <select class="form-select" id="wasteType" name="waste_type" required>
                                    <option value="" selected disabled>Choose...</option>
                                    <option value="plastic">Plastic Bottles / Containers</option>
                                    <option value="metal">Metal Scraps</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="weight" class="form-label">Estimated Weight (kg)</label>
                                <input type="number" class="form-control" id="weight" name="weight"
                                    placeholder="e.g. 5">
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>Pickup Details</legend>

                            <div class="mb-4 p-3 bg-light rounded border">
                                <label class="form-label d-block fw-bold text-secondary mb-3">Pickup Address</label>

                                @auth
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="address_option" id="useSaved"
                                            value="saved" checked onchange="toggleAddress()">
                                        <label class="form-check-label" for="useSaved">
                                            Use my saved profile address: <br>
                                            <span class="text-success fw-bold small">{{ Auth::user()->address }}</span>
                                        </label>
                                    </div>
                                @endauth

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="address_option" id="useNew"
                                        value="new" @guest checked @endguest onchange="toggleAddress()">
                                    <label class="form-check-label" for="useNew">Enter a different address</label>
                                </div>

                                <div class="mt-2" id="newAddressBox" style="@auth display: none; @endauth">
                                    <input type="text" class="form-control form-control-sm" id="customAddress"
                                        name="custom_address" placeholder="123 Main St, Barangay...">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label">Service Days</label>
                                <p class="text-muted fw-bold">-- Every Monday</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Location Type</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="loc_type" id="res"
                                        value="residential" checked>
                                    <label class="form-check-label" for="res">Residential</label>
                                </div>
                            </div>
                        </fieldset>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Confirm Schedule</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAddress() {
            const newAddressRadio = document.getElementById('useNew');
            const newAddressBox = document.getElementById('newAddressBox');
            const customAddressInput = document.getElementById('customAddress');

            if (newAddressRadio.checked) {
                newAddressBox.style.display = 'block';
                customAddressInput.required = true;
            } else {
                newAddressBox.style.display = 'none';
                customAddressInput.required = false;
                customAddressInput.value = '';
            }
        }
    </script>
</body>

</html>
