<?php

namespace App\Http\Controllers;

use App\Domains\Trip\Model\Trip;
use Illuminate\Http\Request;

class TripPlayBackController
{
    public function index(int $trip_id)
    {
        $data = [];
        $trip = Trip::findOrFail($trip_id);
        $data['trip'] = $trip->toArray();
        $data['vehicle'] = $trip->vehicle->toArray();
        $data['positions'] = $trip->positions->toArray();
        return view('playback.index', compact('data'));
    }
}
