<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reporting | Eco-Track</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header-report {
            background: linear-gradient(135deg, #1b4332 0%, #2d6a4f 100%);
            color: white;
            padding: 50px 0 40px;
            border-bottom: 5px solid #ff9f1c;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            margin-top: -30px;
        }

        .btn-warning-custom {
            background: #ff9f1c;
            color: white;
            font-weight: bold;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-warning-custom:hover {
            background: #e68a00;
            color: white;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #ff9f1c;
            box-shadow: 0 0 0 0.2rem rgba(255, 159, 28, 0.25);
        }
    </style>
</head>

<body>

    <header class="header-report text-center">
        <div class="container">
            <h2 class="fw-bold">Report an Incident</h2>
            <p class="mb-3">Help keep our community clean by reporting illegal dumping or hazards.</p>
            <a href="{{ route('homepage') }}" class="text-white text-decoration-none small">← Back to Dashboard</a>
        </div>
    </header>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4 p-md-5">
                    <form id="reportForm" action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="photoEvidence" class="form-label fw-bold">1. Photo Evidence</label>
                            <input class="form-control" type="file" id="photoEvidence" name="photo" accept="image/*" capture="environment" required>
                            <div class="form-text small text-info" id="photoStatus">Upload a photo to automatically extract location data.</div>
                        </div>

                        <div class="mb-4">
                            <label for="location" class="form-label fw-bold">2. Accurate Location</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary fw-bold" type="button" id="btn-gps">📍 Auto Geo-Tag</button>
                                <input type="text" class="form-control" id="location" name="location" placeholder="GPS coordinates or manual address" required>
                            </div>
                            <div class="form-text small">Use GPS powered pins, upload a geo-tagged photo, or type an address manually.</div>
                        </div>

                        <div class="mb-4">
                            <label for="incidentType" class="form-label fw-bold">3. Incident Details</label>
                            <select class="form-select mb-3" id="incidentType" name="incident_type" required>
                                <option value="" selected disabled>Select incident type...</option>
                                <option value="illegal_dumping">Illegal Dumping</option>
                                <option value="overflowing_bin">Overflowing Public Bin</option>
                                <option value="missed_pickup">Missed Scheduled Pickup</option>
                                <option value="other">Other</option>
                            </select>

                            <div class="form-check border p-3 rounded bg-light">
                                <input class="form-check-input" type="checkbox" value="hazardous" id="hazardousCheck" name="priority_triage">
                                <label class="form-check-label text-danger fw-bold" for="hazardousCheck">
                                    ⚠️ Flag as Hazardous Material
                                </label>
                                <div class="small text-muted mt-1">Check this box to trigger immediate escalation to specialized crews.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold">4. Optional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Provide any additional context here..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-warning-custom w-100 py-3 rounded-pill shadow-sm">Submit Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gpsBtn = document.getElementById('btn-gps');
            const locationInput = document.getElementById('location');
            const photoInput = document.getElementById('photoEvidence');
            const photoStatus = document.getElementById('photoStatus');

            // --- FEATURE 1: Device Geolocation (Button Click) ---
            gpsBtn.addEventListener('click', function() {
                if (navigator.geolocation) {
                    gpsBtn.innerText = "⏳ Locating...";
                    gpsBtn.disabled = true;

                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            // Success
                            const lat = position.coords.latitude.toFixed(6);
                            const lng = position.coords.longitude.toFixed(6);
                            locationInput.value = `${lat}, ${lng}`;
                            
                            gpsBtn.innerText = "✅ Found!";
                            setTimeout(() => { 
                                gpsBtn.innerText = "📍 Auto Geo-Tag"; 
                                gpsBtn.disabled = false; 
                            }, 3000);
                        },
                        function(error) {
                            // Error handling
                            alert("Unable to retrieve your location. Please check your browser permissions or enter it manually.");
                            gpsBtn.innerText = "📍 Auto Geo-Tag";
                            gpsBtn.disabled = false;
                        }
                    );
                } else {
                    alert("Geolocation is not supported by your browser.");
                }
            });

            // --- FEATURE 2: Extract EXIF GPS from Uploaded Photo ---
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    photoStatus.innerText = "Scanning photo for location data...";
                    
                    EXIF.getData(file, function() {
                        const latArray = EXIF.getTag(this, "GPSLatitude");
                        const lngArray = EXIF.getTag(this, "GPSLongitude");
                        const latRef = EXIF.getTag(this, "GPSLatitudeRef") || "N";
                        const lngRef = EXIF.getTag(this, "GPSLongitudeRef") || "W";

                        if (latArray && lngArray) {
                            // Convert EXIF Degrees, Minutes, Seconds arrays to Decimal Degrees
                            let lat = convertDMSToDD(latArray[0], latArray[1], latArray[2], latRef);
                            let lng = convertDMSToDD(lngArray[0], lngArray[1], lngArray[2], lngRef);
                            
                            locationInput.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                            photoStatus.innerText = "✅ Location successfully extracted from photo!";
                            photoStatus.classList.replace('text-info', 'text-success');
                        } else {
                            photoStatus.innerText = "⚠️ No location data found in this photo. Please enter manually or use the Geo-Tag button.";
                            photoStatus.classList.replace('text-info', 'text-warning');
                        }
                    });
                }
            });

            // Helper function to convert Degrees Minutes Seconds to Decimal Degrees
            function convertDMSToDD(degrees, minutes, seconds, direction) {
                let dd = degrees + (minutes / 60) + (seconds / 3600);
                if (direction == "S" || direction == "W") {
                    dd = dd * -1;
                }
                return dd;
            }
        });
    </script>
</body>
</html>