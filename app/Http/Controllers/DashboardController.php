<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController
{
    public function index() {
        $vehicles = DB::table('vehicle')
            ->join('vehicle_roles', 'vehicle.id', '=', 'vehicle_roles.vehicle_id')
            ->join('user_roles', 'vehicle_roles.id', '=', 'user_roles.role_id')
            ->get()
            ->toArray();
        return view('dashboard.index', compact('vehicles'));
    }

    public function vehicleLastStatus(Request $request)
    {
        $vehicles = $request->input('vehicles');
        $status = [];
        foreach ($vehicles as $vehicle) {
            $lastStatus = DB::table('position')
                ->where('vehicle_id', $vehicle)
                ->orderBy('created_at', 'desc')
                ->first();
            $status[] = $lastStatus;
        }
        return response()->json($status);
    }
}
