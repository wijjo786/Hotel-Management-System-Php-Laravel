<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Request;
use Illuminate\Support\Facades\DB;

class RoomsController extends Controller
{
    //
    public function viewForm () { return view('addRooms'); }
    public function index () { return view('rooms'); }

    function viewRooms() {
        $roomData = DB::select('select * from rooms');     
        return view('rooms', ["roomData"=>$roomData]);
    }
    function storeRoom() {     
    
        $number = Request::input('number');
        $type = Request::input('type');
        $status = Request::input('status');
        $category = Request::input('category');

        $exist = DB::select('select * from rooms where RNumber = ? ', [$number]);
        if(count($exist) == 0){
            DB::insert('insert into rooms (RNumber, RType, Status, Category) values (?, ?, ?, ?)', [$number , $type, $status, $category]);
            $msg = "Room Added Successfully";
            return redirect()->back() ->with('alert', $msg);          
        }else{
            $msg = "Room Already Exist";
            return redirect('/addRooms')->with('alert', $msg);
        }

        
    }
    function viewEdit() {
        $id= request('id'); 
        $roomData = DB::select('select * from rooms where id = ?',[$id]);
        $showAll = DB::select('select * from rooms');

        foreach($roomData as $r){
            $status = $r->Status;
        }

        if($r->Status != "Reserved"){
            return view('editRoom', ["roomData"=>$roomData,'id'=>$id]);
        }
        else{
            // Session::flash('alert','Room is Reserved\nYou Can not Edit it');
            // return view('rooms', ["roomData"=>$showAll]);
            return redirect('/rooms')->with('alert', 'Room is Reserved You Can not Edit it');
        }
       
    }

    function updateRooms($id) {
        $number = Request::input('number');
        $type = Request::input('type');
        $status = Request::input('status');
        $category = Request::input('category');

        $exist = DB::select('select * from rooms where RNumber = ? ', [$number]);
        if(count($exist) == 0){
            DB::update('update rooms set RType=?, RNumber=?, Status=?, Category=? where id= ?', [$type, $number,$status, $category, $id]);
            $msg = "Room Updated Successfully";
            return redirect('/rooms')->with('alert', 'Room Updated Successfully');
        }else{
            $msg = "Room Already Exist";
            return redirect('/editRoom')->with('alert', $msg);
        }

        
    }
    function deleteRoom() {
        $id= request('id');

        $roomData = DB::select('select * from rooms where id = ?',[$id]);
        foreach($roomData as $r){
            $status = $r->Status;
        }
        if($r->Status != "Reserved"){
            DB::delete('delete from rooms where id= ?', [$id]);
            return redirect('rooms');        
        }else{
            return redirect('/rooms')->with('alert', 'Room is Reserved You Can not delete it');
        }
        
    }
}
