<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    public function index () {
         $adminData = DB::select('select * from admin');     
         $employee = DB::select('select * from employees');
         $rooms = DB::select('select * from rooms');
         $reserved = DB::select('select * from rooms where Status!="Empty" ');
         $reservedCount = count($reserved);
         $roomsCount = count($rooms);
         $employeeCount = count($employee);
        return view('admin', ["adminData"=>$adminData], ["employeeCount"=>$employeeCount, "roomsCount"=>$roomsCount, "reservedCount" => $reservedCount]);
        }
    public function storeAdmin(){
        $Fname = Request::input('fname');
        $Lname = Request::input('lname');
        $Email = Request::input('email');
        $pass = Request::input('pass');
        $Gender = Request::input('gender');
        $age = Request::input('age');

        $hash = password_hash($pass,PASSWORD_DEFAULT);

        $exist = DB::select('select * from admin where email = ? ', [$Email]);
        if(count($exist) == 0){
            DB::insert('insert into admin(Fname, Lname, Email, Password, Age, Gender) values(?, ?, ?, ?, ?, ?)', [$Fname, $Lname, $Email, $hash, $age, $Gender]);
            return view('welcome');
        }else{
            $msg = "Email Already Taken";
            return redirect('/signup')->with('alert', $msg);
        }

    }

}

