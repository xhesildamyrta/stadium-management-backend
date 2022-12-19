<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function reserve(Request $request) {
        $request->validate([
            'date' => 'required',
            'seat_id' => 'required|integer',
            'price' => 'required',
            'event_id' => 'required|integer',
        ]);

        $isReserved = Ticket::query()->where([
            ['date', $request->input('date')],
            ['seat_id', $request->input('seat_id')],
            ['event_id', $request->input('event_id')]
        ])->get();

        if ($isReserved->count() != 0)
            return response()->json(['message' => 'This ticket is not available!'], 200);

        try {
            Ticket::query()->create([
                'date' => $request->input('date'),
                'price' => $request->input('price'),
                'event_id' => $request->input('event_id'),
                'seat_id' => $request->input('seat_id'),
            ]);
        }
        catch (\Exception $exception) {
            return response()->json(['message' => 'Reservation was not successful! Please try again later'], 503);
        }

        return response()->json(['message' => 'Reservation was successful!'], 200);
    }

    public function availableSeats($event) {
        $available = Seat::query()->whereNotIn('id', function ($query) use ($event) {
            $query->select('seat_id')->from('tickets')->where('event_id', '=', $event);
        })->get()->groupBy('zone');

        return $available;
    }

    public function zones() {
        $zones = Seat::query()->distinct()->pluck('zone');

        return json_encode($zones);
    }

    public function getAvailableSeatsByZone($event, $zone) {
        $zone = strtoupper($zone);

        $available = Seat::query()->whereNotIn('id', function ($query) use ($event, $zone) {
            $query->select('seat_id')->from('tickets')->where('event_id', '=', $event);
        })->where('zone', '=', $zone)->get(['chair_number', 'id']);

        return json_encode($available);
    }
}
