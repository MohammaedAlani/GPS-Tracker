<?php

namespace App\Http\Controllers;

use App\Domains\Device\Model\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController
{
    public function index() {
        $vehicles = DB::table('vehicle')
            ->join('vehicle_roles', 'vehicle.id', '=', 'vehicle_roles.vehicle_id')
            ->join('user_roles', 'vehicle_roles.role_id', '=', 'user_roles.role_id')
            ->where('user_roles.user_id', Auth::user()->id)   // ← required to filter by the current user
            ->select('vehicle.*')
            ->get();

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

            $lastStatus = $lastStatus ? (array) $lastStatus : [];

            $deviceLastSeen = Device::where('vehicle_id', $vehicle)
                ->orderBy('last_seen', 'desc')
                ->value('last_seen');

            $positionCreatedAt = $lastStatus['created_at'] ?? null;

            $lastStatus['last_seen'] = collect([$positionCreatedAt, $deviceLastSeen])
                ->filter()
                ->map(fn($date) => Carbon::parse($date))
                ->sortDesc()
                ->first()
                ?->toDateTimeString();

            $status[] = $lastStatus;
        }

        return response()->json($status);
    }
}
