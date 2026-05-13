<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class OperatorController extends Controller
{

    public function dashboard(Request $request)
    {
        $pointsAmount = Setting::where('key', 'conversion_points_amount')->value('value') ?? 100;
        $pesoEquivalent = Setting::where('key', 'conversion_peso_equivalent')->value('value') ?? 1;

        return view('operator.dashboard', compact(
            'pointsAmount',
            'pesoEquivalent'
        ));
    }

    public function updateConversionRate(Request $request)
    {

        $request->validate([
            'points_amount' => 'required|integer|min:1',
            'peso_equivalent' => 'required|numeric|min:0',
        ]);

        Setting::updateOrCreate(
            ['key' => 'conversion_points_amount'],
            ['value' => $request->points_amount]
        );

        Setting::updateOrCreate(
            ['key' => 'conversion_peso_equivalent'],
            ['value' => $request->peso_equivalent]
        );

        return back()->with('success', 'Conversion rate updated successfully!');
    }
}
