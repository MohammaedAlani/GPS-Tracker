<?php

namespace App\Http\Controllers;

use App\Domains\Device\Model\Device;
use App\Domains\Timezone\Model\Timezone;
use App\Domains\Vehicle\Model\Vehicle;
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
            $lastStatus = [
                'vehicle_id' => $vehicle,
                'latitude' => null,
                'longitude' => null,
                'speed' => 0,
                'direction' => 0,
                'created_at' => null,
                'timezone_id' => null,
            ];

            $position = DB::table('position')
                ->where('vehicle_id', $vehicle)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($position) {
                $lastStatus = array_merge($lastStatus, (array)$position);
            }

            $deviceLastSeen = Device::where('vehicle_id', $vehicle)
                ->orderBy('last_seen', 'desc')
                ->first();

            $lastStatus['last_seen'] = collect([
                $lastStatus['created_at'],
                $deviceLastSeen->last_seen,
            ])
                ->filter()
                ->map(fn($date) => Carbon::parse($date))
                ->sortDesc()
                ->first()
                ?->toDateTimeString();

            $lastStatus['timezone_id'] = Timezone::find(Vehicle::find($vehicle)->timezone_id);

            $status[] = $lastStatus;
        }

        return response()->json($status);
    }
}
