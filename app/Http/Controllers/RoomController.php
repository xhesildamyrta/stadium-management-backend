<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\RoomRoomType;
use App\Models\HotelReservation;


use Illuminate\Http\Request;

class RoomController extends Controller
{

    public function getAllRooms()
    {
        $rooms =Room::query()->join('room_types', function ($join) {
            $join->on('rooms.type_id', '=', 'room_types.id');
        })->get(['rooms.id','rooms.name', 'number', 'capacity','type', 'image_path']);

        return json_encode($rooms);
    }

    public function showSpecificRoom($id)
    {
        $room_data = Room::find($id);

        return json_encode($room_data);
    }

    public function groupRoomsByType() {
        $groups = Room::query()->join('room_types', function ($join) {
            $join->on('rooms.type_id', '=', 'room_types.id');
        })->get()->groupBy('type');

        return json_encode($groups);
    }

    public function getRoomsByType($type){
        $rooms = Room::query()->join('room_types', function ($join) use ($type) {
            $join->on('rooms.type_id', '=', 'room_types.id')
                ->where('room_types.type','=',$type);
        })->get(['rooms.id','rooms.name', 'number', 'capacity','type', 'image_path']);

        return json_encode($rooms);
    }

    public function getAvailableRooms() {
        $available = Room::query()->addSelect(['available-rooms' => function($query) {
            $query->select('room_id')
                ->from('hotel_reservations')
                ->where([['start_date', '>=', date('Y-m-d')], ['end_date', '>=', date('Y-m-d')]])
                ->orWhere([['start_date', '<=', date('Y-m-d')], ['end_date', '<=', date('Y-m-d')]])
                ->whereColumn('id', 'room_id')
                ->get();
        }])->get();

        return $available;
    }

    public function getAvailableRoomsByType($type) {
        $type = ucwords(str_replace('-', ' ', $type));

        $available = $this->getAvailableRooms()->toQuery()->join('room_types', function ($join) use ($type) {
            $join->on('rooms.type_id', '=', 'room_types.id')
                ->where('room_types.type', '=', $type);
        })->get();

        return $available;
    }

    public function getRoomTypes() {
        $types = RoomType::query()->distinct()->pluck('type');

        return json_decode($types);
    }

}
