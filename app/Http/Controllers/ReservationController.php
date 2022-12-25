<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    //
    public function index () {
        $Data = DB::select('select * from rooms, guests where rooms.RNumber = guests.RoomNo');     
        $rooms = DB::select('select * from rooms');
         $reserved = DB::select('select * from rooms where Status!="Empty" ');
         $reservedCount = count($reserved);
         $roomsCount = count($rooms);
       return view('reservations', ["Data"=>$Data, "roomsCount"=>$roomsCount, "reservedCount" => $reservedCount]);
       }
}
