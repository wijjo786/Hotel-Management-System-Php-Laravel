<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\DB;


class employeeHomeController extends Controller
{
    public function index () {

        $employeeData = DB::select('select * from employees where Status = "online"');
        $guests = DB::select('select * from guests');
        $rooms = DB::select('select * from rooms');
        $reserved = DB::select('select * from rooms where Status!="Empty" ');
        $reservedCount = count($reserved);
        $roomsCount = count($rooms);
        $guestsCount = count($guests);

        return view('employeeHome', ["employeeData"=>$employeeData, "loginData"=>$employeeData], ["guestsCount"=>$guestsCount, "roomsCount"=>$roomsCount, "reservedCount" => $reservedCount]); 
        }   
}
