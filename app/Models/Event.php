<?php

namespace App\Models;
use App\Models\Ticket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
}
