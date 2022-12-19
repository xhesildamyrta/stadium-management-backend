<?php

namespace App\Http\Controllers;

use App\Models\HotelReservation;
use App\Models\Room;
use Illuminate\Http\Request;

class HotelReservationsController extends Controller
{
    public function reserve(Request $request) {
        $request->validate([
            'room_id' => 'required|integer',
            'name' => 'required',
            'NID' => 'required|max:10',
            'phone' => 'max:11',
            'start_date' => 'required|after_or_equal:today',
            'end_date' => 'required|after_or_equal:start_date'
        ]);

        $isReserved = HotelReservation::query()
            ->where([
                ['room_id', $request->input('room_id')],
                ['start_date', '>=', $request->input('start_date')],
                ['end_date', '>=', $request->input('end_date')],
                ['start_date', '<=', $request->input('end_date')]
            ])
            ->orWhere([
                ['room_id', $request->input('room_id')],
                ['start_date', '<=', $request->input('start_date')],
                ['end_date', '<=', $request->input('end_date')],
                ['end_date', '>=', $request->input('start_date')]
            ])
            ->orWhere([
                ['room_id', $request->input('room_id')],
                ['start_date', '>=', $request->input('start_date')],
                ['end_date', '<=', $request->input('end_date')],
            ])
            ->orWhere([
                ['room_id', $request->input('room_id')],
                ['start_date', '<=', $request->input('start_date')],
                ['end_date', '>=', $request->input('end_date')],
            ])
            ->get();

        if ($isReserved->count() != 0)
            return response()->json(['message' => 'This room is not available!'], 200);

        try {
            HotelReservation::query()->create([
                'room_id' => $request->input('room_id'),
                'name' => $request->input('name'),
                'NID' => $request->input('NID'),
                'phone' => $request->input('phone'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);
        }
        catch (\Exception $exception) {
            return response()->json(['message' => 'Reservation was not successful! Please try again later'], 503);
        }

        return response()->json(['message' => 'Reservation was successful!'], 200);
    }

    public function reservedDates($room) {
        $reservedDates = HotelReservation::query()
            ->where('room_id', '=', $room)
            ->get(['start_date', 'end_date'])->toArray();

        $rangArray = [];
        foreach ($reservedDates as $dates) {
            for ($currentDate = strtotime($dates['start_date']); $currentDate <= strtotime($dates['end_date']); $currentDate += (86400)) {

                $date = date('Y-m-d', $currentDate);
                $rangArray[] = $date;
            }
        }

        return json_encode($rangArray);
    }
}
