<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\DB;


class GuestController extends Controller
{
    public function viewForm () {
        $rooms = DB::select('select RNumber from rooms where Status="Empty"');
         return view('addGuest', ["rooms"=>$rooms]); 
        }
    public function index () { return view('guest'); }
    function storeGuest() {     
        
        $firstname = Request::input('fname');
        $lastname = Request::input('lname');
        $email = Request::input('email');
        $gender = Request::input('gender');
        $age = Request::input('age');
        $phone = Request::input('phone');
        $days = Request::input('days');
        $room = Request::input('room');

        $exist = DB::select('select * from guests where email = ? ', [$email]);
        if(count($exist) == 0){
            $rooms = DB::select('select * from rooms where RNumber = ? ', [$room]);
            foreach($rooms as $r){
                $category = $r->Category;
            }
            DB::insert('insert into guests (FName, LName, Email, Age, Gender, PhoneNo, Days, RoomNo, Category) values ( ?, ?, ?, ?, ?,?, ?, ?, ?)', [$firstname , $lastname, $email, $age, $gender, $phone, $days, $room, $category]);
            DB::update('update rooms set Status = "Reserved" where RNumber = ? ', [$room]);
            $msg = "Guest Added Successfully";
            return redirect()->back() ->with('alert', $msg);
        }else{
            $msg = "Guest Already Exist";
            return redirect('/addGuest')->with('alert', $msg);
        }
    }
    function viewGuest() {
        $guestData = DB::select('select * from guests');     
        $rooms = DB::select('select RNumber from rooms where Status="Empty"');
        return view('guest', ["guestData"=>$guestData, "rooms"=>$rooms]);
    }
    function viewEdit() {
        $id= request('id'); 
        $guestData = DB::select('select * from guests where id = ?',[$id]);
        $rooms = DB::select('select RNumber from rooms where Status="Empty"');
        return view('guestEdit', ["guestData"=>$guestData,'id'=>$id, "rooms"=>$rooms]);
    }
    function updateGuest($id) {
        $fname = Request::input('fname');
        $lname = Request::input('lname');
        $email = Request::input('email');
        $gender = Request::input('gender');
        $age = Request::input('age');
        $days = Request::input('days');
        $phone = Request::input('phone');
        $room = Request::input('room');
        
        $previousRoom = DB::select('select * from guests where id = ? ', [$id]);
        foreach($previousRoom as $data){
            $changeRoom = $data->RoomNo;
        }
        DB::update('update rooms set Status = "Empty" where RNumber = ? ', [$changeRoom]);
        $rooms = DB::select('select * from rooms where RNumber = ? ', [$room]);
        foreach($rooms as $r){
            $category = $r->Category;
        }

        DB::update('update guests set FName=?, LName=?, Email=?, Age=?, Gender=?, PhoneNo=?, Days=?, RoomNo=?, Category=? where id= ?', [$fname, $lname,$email, $age, $gender, $phone, $days, $room, $category, $id]);
        DB::update('update rooms set Status = "Reserved" where RNumber = ? ', [$room]);
        $msg = "Data Updated";
        return redirect('guest')->with('alert', $msg);
    }
    function deleteGuest() {
        $id= request('id');
        $room = DB::select('select * from guests where id = ? ', [$id]);
        foreach($room as $r){
            $number = $r->RoomNo;
        }
        DB::update('update rooms set Status = "Empty" where RNumber = ? ', [$number]);
        DB::delete('delete from guests where id= ?', [$id]);
        $msg = "Guest Deleted";
        return redirect('guest')->with('alert', $msg);
    } 
}
