<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelReservation extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'client_id', 'start_date', 'end_date', 'name', 'NID', 'phone'];
}
