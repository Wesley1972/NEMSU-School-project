<?php

use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\OperatorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Report;
use Illuminate\Support\Facades\Cache;
use App\Models\Redemption;

// 4. Processes the new password and updates the database
Route::post('/reset-password', [PasswordResetController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

// 1. Shows the Forgot Password page when the user clicks the link
Route::get('/forgot-password', function () {
    return view('forgot-password');
})->middleware('guest')->name('password.request');

// 2. Processes the email submission (Simulated for now)
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

// Process the Incident Report
Route::post('/report', function (Request $request) {

    $request->validate([
        'photo' => 'required|image|max:5120', // Max 5MB image
        'location' => 'required|string|max:255',
        'incident_type' => 'required|string',
        'notes' => 'nullable|string',
    ]);

    $path = null;
    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('reports', 'public');
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();

    $user->reports()->create([
        'photo_path' => $path,
        'location' => $request->location,
        'incident_type' => $request->incident_type,
        'is_hazardous' => $request->has('priority_triage'),
        'notes' => $request->notes,
    ]);

    return redirect()->route('homepage')->with('status', 'Incident reported successfully! Thank you for keeping the community clean.');
})->middleware('auth')->name('report.store');

Route::post('/schedule', function (Request $request) {
    $request->validate([
        'waste_type' => 'required|string',
        'weight' => 'nullable|numeric',
        'address_option' => 'required|in:saved,new',
        'custom_address' => 'required_if:address_option,new|nullable|string',
        'loc_type' => 'required|string',
    ]);

    $finalAddress = $request->address_option === 'saved'
        ? Auth::user()->address
        : $request->custom_address;

    /** @var \App\Models\User $user */
    $user = Auth::user();

    $user->schedules()->create([
        'waste_type' => $request->waste_type,
        'weight' => $request->weight,
        'pickup_address' => $finalAddress,
        'location_type' => $request->loc_type,
    ]);

    return redirect()->route('homepage')->with('status', 'Pickup scheduled successfully!');
})->middleware('auth')->name('schedule.store');

Route::post('/profile/update', function (Request $request) {
    $user = Auth::user();

    $request->validate([
        'email' => 'required|email|unique:users,email,' . $user->id,
        'address' => 'required|string|max:500',
    ]);
    /** @var \App\Models\User $user */
    $user->update([
        'email' => $request->email,
        'address' => $request->address,
    ]);

    return redirect()->route('profile')->with('status', 'Profile information updated successfully!');
})->middleware('auth')->name('profile.update');

Route::get('/change-password', function () {
    return view('change-password');
})->middleware('auth')->name('password.change');

Route::post('/change-password', function (Request $request) {
    $request->validate([
        'current_password' => ['required', 'current_password'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $request->user()->update([
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('profile')->with('status', 'Password successfully updated!');
})->middleware('auth')->name('password.update');

Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('homepage');
})->name('logout');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
        'role' => ['required', 'string']
    ]);

    $remember = $request->has('remember');

    $loginData = ['email' => $request->email, 'password' => $request->password];

    if (Auth::attempt($loginData, $remember)) {
        $request->session()->regenerate();

        if (Auth::user()->role === 'operator') {
            return redirect()->route('operator.dashboard');
        }

        return redirect()->route('homepage');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
})->name('login.authenticate');

Route::post('/register', function (Request $request) {
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'address' => 'required|string|max:500',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'address' => $request->address,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    Auth::login($user);

    return redirect()->route('homepage');
})->name('register.store');


Route::get('/', function () {
    return view('homepage');
})->name('homepage');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/schedule', function () {
    return view('schedule');
})->name('schedule');

Route::get('report', function () {
    return view('report');
})->name('report');

Route::get('/earn', function () {
    $schedules = Auth::user()->schedules()->latest()->get();
    $redemptions = Auth::user()->redemptions()->latest()->get();

    // NEW: Fetch the user's incident reports
    $reports = Auth::user()->reports()->latest()->get();

    // Fetch Settings and Cache Rates
    $pointsAmount = \App\Models\Setting::where('key', 'conversion_points_amount')->value('value') ?? 100;
    $pesoEquivalent = \App\Models\Setting::where('key', 'conversion_peso_equivalent')->value('value') ?? 1;

    // NEW: Fetch the report reward rate (defaults to 50 if not set)
    $reportRate = \Illuminate\Support\Facades\Cache::get('report_rate', 50);

    return view('earn', compact(
        'schedules',
        'redemptions',
        'reports',
        'pointsAmount',
        'pesoEquivalent',
        'reportRate'
    ));
})->middleware('auth')->name('earn');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/operator-dashboard', function (Illuminate\Http\Request $request) {
    if (Auth::user()->role !== 'operator') {
        return redirect()->route('homepage');
    }

    $selectedUserId = $request->query('user_id');
    $view = $request->query('view', 'pending');

    $residents = User::where('role', 'resident')->orderBy('first_name')->get();
    $schedulesQuery = Schedule::with('user');
    $reportsQuery = Report::with('user');

    if ($view === 'completed') {
        $schedulesQuery->where('status', 'completed');
        $reportsQuery->where('status', 'resolved');
    } else {
        $schedulesQuery->where('status', 'pending');
        $reportsQuery->where('status', 'pending');
    }

    $selectedUser = null;

    if ($selectedUserId) {
        $schedulesQuery->where('user_id', $selectedUserId);
        $reportsQuery->where('user_id', $selectedUserId);

        $selectedUser = User::find($selectedUserId);

    }

    $plasticRate = Cache::get('plastic_rate', 10);
    $metalRate = Cache::get('metal_rate', 15);
    $reportRate = Cache::get('report_rate', 50);

    return view('operator-dashboard', [
        'schedules' => $schedulesQuery->latest()->get(),
        'reports' => $reportsQuery->latest()->get(),
        'residents' => $residents,
        'selectedUserId' => $selectedUserId,
        'selectedUser' => $selectedUser,
        'plasticRate' => $plasticRate,
        'metalRate' => $metalRate,
        'reportRate' => $reportRate,
    ]);
})->middleware('auth')->name('operator.dashboard');

Route::post('/operator/rates', function (Request $request) {
    $request->validate([
        'plastic_rate' => 'required|numeric|min:0',
        'metal_rate' => 'required|numeric|min:0',
    ]);

    Cache::forever('plastic_rate', $request->plastic_rate);
    Cache::forever('metal_rate', $request->metal_rate);

    return back()->with('status', 'Reward rates successfully updated!');
})->middleware('auth')->name('operator.rates');

Route::post('/schedule/{id}/complete', function ($id) {
    $schedule = Schedule::findOrFail($id);

    if ($schedule->status === 'completed') {
        return back();
    }

    $plasticRate = Cache::get('plastic_rate', 10);
    $metalRate = Cache::get('metal_rate', 15);

    $rate = strtolower($schedule->waste_type) === 'plastic' ? $plasticRate : (strtolower($schedule->waste_type) === 'metal' ? $metalRate : 0);
    $pointsEarned = ($schedule->weight ?? 0) * $rate;

    $schedule->update([
        'status' => 'completed',
        'points_earned' => $pointsEarned
    ]);

    if ($schedule->user) {
        $schedule->user->increment('points', $pointsEarned);
        $schedule->user->increment('total_points_earned', $pointsEarned);
    }

    return back()->with('status', "Pickup completed! $pointsEarned points awarded to resident.");
})->middleware('auth')->name('schedule.complete');

Route::post('/report/{id}/resolve', function ($id) {
    $report = Report::findOrFail($id);

    // Prevent double rewarding if already resolved
    if ($report->status === 'resolved') {
        return back();
    }

    // 1. Fetch the current reward rate from Cache
    $currentRate = Cache::get('report_rate', 50);

    // 2. Update the report: Mark as resolved AND save the point snapshot
    $report->update([
        'status' => 'resolved',
        'points_awarded' => $currentRate // This freezes the rate for this record
    ]);

    // 3. Award the frozen amount to the user
    if ($report->user && $currentRate > 0) {
        $report->user->increment('points', $currentRate);
        $report->user->increment('total_points_earned', $currentRate);
    }

    return back()->with('status', "Incident resolved! $currentRate points awarded to the resident.");
})->middleware('auth')->name('report.resolve');

Route::post('/redeem-points', function (Request $request) {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $amountToRedeem = (int) $request->input('amount');
    $paymentMethod = $request->input('payment_method', 'Cash');

    if ($amountToRedeem <= 0 || $amountToRedeem > $user->points) {
        return response()->json(['success' => false, 'message' => 'Invalid amount or insufficient points.']);
    }

    // 1. Deduct points
    $user->points -= $amountToRedeem;
    $user->points_redeemed += $amountToRedeem;
    $user->save();

    // 2. Save the redemption record
    $redemption = $user->redemptions()->create([
        'amount' => $amountToRedeem,
        'payment_method' => $paymentMethod,
    ]);

    // 3. Calculate cash value to send to the Javascript frontend instantly
    $pointsAmount = \App\Models\Setting::where('key', 'conversion_points_amount')->value('value') ?? 100;
    $pesoEquivalent = \App\Models\Setting::where('key', 'conversion_peso_equivalent')->value('value') ?? 1;
    $cashValue = number_format(($amountToRedeem / $pointsAmount) * $pesoEquivalent, 2);

    return response()->json([
        'success' => true,
        'new_balance' => $user->points,
        'redemption_id' => $redemption->id,
        'cash_value' => $cashValue // Send this to Javascript
    ]);
})->middleware('auth')->name('rewards.redeem');

// User Cancel Redemption Route
Route::post('/redeem-points/cancel/{id}', function ($id) {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $redemption = $user->redemptions()->find($id);

    if (!$redemption) {
        return response()->json(['success' => false, 'message' => 'Redemption not found.']);
    }

    // NEW: Security Check - Prevent canceling if already completed
    if ($redemption->status !== 'pending') {
        return response()->json(['success' => false, 'message' => 'Cannot cancel a confirmed redemption.']);
    }

    // Restore the points
    $user->points += $redemption->amount;
    $user->points_redeemed -= $redemption->amount;
    $user->save();

    $redemption->delete();

    return response()->json([
        'success' => true,
        'new_balance' => $user->points
    ]);
})->middleware('auth')->name('rewards.cancel');

// NEW: Admin Custom Reward Route
Route::post('/rewards/{id}/custom', function (Request $request, $id) {
    // Make sure only operators can issue custom rewards
    if (Auth::user()->role !== 'operator') {
        abort(403);
    }

    $request->validate([
        'custom_reward' => 'required|string|max:255',
    ]);

    $redemption = Redemption::findOrFail($id);

    // Update the database record
    $redemption->custom_reward = $request->custom_reward;
    $redemption->status = 'completed'; // Mark as completed since the custom reward was given
    $redemption->save();

    return back()->with('status', 'Custom reward saved and marked as completed!');
})->middleware('auth')->name('rewards.custom');

Route::post('/redeem-points/confirm/{id}', function ($id) {
    // Make sure only operators can confirm
    if (Auth::user()->role !== 'operator') {
        abort(403);
    }

    $redemption = Redemption::findOrFail($id);
    $redemption->update(['status' => 'completed']);

    return back()->with('status', 'Redemption successfully confirmed!');
})->middleware('auth')->name('rewards.confirm');

Route::get('/admin/rewardPage', function (Request $request) {
    $selectedUser = null;

    if ($request->has('user_id') && $request->user_id != '') {
        $selectedUser = User::find($request->user_id);
    }

    // 1. Fetch Conversion Settings
    $pointsAmount = \App\Models\Setting::where('key', 'conversion_points_amount')->value('value') ?? 100;
    $pesoEquivalent = \App\Models\Setting::where('key', 'conversion_peso_equivalent')->value('value') ?? 1;

    // 2. Pass them to the view
    return view('admin-reward-page', compact('selectedUser', 'pointsAmount', 'pesoEquivalent'));
})->name('rewardPage');

Route::post('/operator/conversion-rate', [OperatorController::class, 'updateConversionRate'])
    ->name('operator.updateConversion');

Route::post('/operator/report-rate', function (Request $request) {
    $request->validate([
        'report_rate' => 'required|numeric|min:0',
    ]);

    Cache::forever('report_rate', $request->report_rate);

    return back()->with('status', 'Incident report reward rate successfully updated!');
})->middleware('auth')->name('operator.reportRate');
