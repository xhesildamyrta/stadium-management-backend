<?php

use App\Http\Controllers\EventsController;
use App\Http\Controllers\HotelReservationsController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api']], function () {
    Route::group(['prefix' => 'events'], function () {
        Route::get('/all', [EventsController::class, 'getAllEvents']);
        Route::get('/latest', [EventsController::class, 'getLatestEvents']);
        Route::get('/most-attended', [EventsController::class, 'mostAttendedEvents']);
        Route::get('/upcoming', [EventsController::class, 'getUpcomingEvents']);
        Route::get('/upcoming/{type}', [EventsController::class, 'groupUpcomingEventsByType']);
        Route::get('/past', [EventsController::class, 'getPastEvents']);
        Route::get('/past/{type}', [EventsController::class, 'groupPastEventsByType']);
        Route::get('/types', [EventsController::class, 'groupEventsByType']);
        Route::get('/type/{type}', [EventsController::class, 'getEventByType']);
        Route::get('/{id}', [EventsController::class, 'getSpecificEvent']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/all', [UserController::class, 'getAllUsers']);
        Route::get('/roles', [UserController::class, 'groupUsersByRoles']);
        Route::get('/role/{role}', [UserController::class, 'getUsersByRole']);
        Route::get('/{nid}', [UserController::class, 'getUserByNID']);
    });

    Route::group(['prefix' => 'rooms'], function () {
        Route::get('/all', [RoomController::class, 'getAllRooms']);
        Route::get('/room-types', [RoomController::class, 'getRoomTypes']);
        Route::get('/room-type-groups', [RoomController::class, 'groupRoomsByType']);
        Route::get('/room-types/{type}', [RoomController::class, 'getRoomsByType']);
        Route::get('/available-rooms', [RoomController::class, 'getAvailableRooms']);
        Route::get('/available-rooms/{type}', [RoomController::class, 'getAvailableRoomsByType']);
        Route::get('/{id}', [RoomController::class, 'showSpecificRoom']);
    });

    Route::group(['prefix' => 'hotel'], function () {
        Route::post('/reserve', [HotelReservationsController::class, 'reserve']);
        Route::get('/reserved-dates/{room}', [HotelReservationsController::class, 'reservedDates']);
    });

    Route::group(['prefix' => 'ticket'], function () {
        Route::post('/reserve', [TicketController::class, 'reserve']);
        Route::get('/available-tickets/{event}', [TicketController::class, 'availableSeats']);
        Route::get('/zones', [TicketController::class, 'zones']);
        Route::get('/seats/{event}/{zone}', [TicketController::class, 'getAvailableSeatsByZone']);
    });
});
