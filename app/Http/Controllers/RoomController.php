<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function show($roomId)
{
    $room = Room::find($roomId);
    return response()->json($room);
}
}
