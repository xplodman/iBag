<?php

namespace App\Http\Controllers;

use App\Models\Bin;
use Illuminate\Http\Request;
use App\Models\BinReading;

class BinReadingController extends Controller
{
    public function list()
    {
        // Validate that the user has an associated bin
        if (!auth()->user() || !auth()->user()->bin) {
            return response()->json(['error' => 'User does not have an associated bin'], 404);
        }

        // Paginate sensor readings in descending order by created_at
        $sensorReadings = BinReading::where('bin_id', auth()->user()->bin->id)
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(5);

        // Return sensor readings as JSON response
        return response()->json($sensorReadings);
    }

    public function show()
    {
        // Validate that the user has an associated bin
        if (!auth()->user() || !auth()->user()->bin) {
            return response()->json(['error' => 'User does not have an associated bin'], 404);
        }

        // Get the latest sensor readings for the bin
        $sensorReadings = BinReading::where('bin_id', auth()->user()->bin->id)
                                    ->latest()->first();

        // Return sensor readings as JSON response
        return response()->json($sensorReadings);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'token' => 'required|string|exists:bins,token',
            'sensor_readings' => 'required|array',
            'sensor_readings.*.sensor_name' => 'required|string',
            'sensor_readings.*.value' => 'required|numeric',
        ]);

        // Retrieve bin ID based on the provided token
        $bin = Bin::where('token', $validatedData['token'])->first();

        $jsonData = [];
        foreach ($validatedData['sensor_readings'] as $sensor_reading) {
            $jsonData[$sensor_reading['sensor_name']] = $sensor_reading['value'];
        }

        // Create a new BinReading record
        $binReading = new BinReading();
        $binReading->bin_id = $bin->id;
        $binReading->sensor_readings = $jsonData;
        $binReading->save();

        // Return a response
        return response()->json(['message' => 'Bin reading recorded successfully', 'data' => $binReading], 201);
    }
}
