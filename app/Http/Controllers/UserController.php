<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //

    
    function matchAdmin() {
        $email = Request::input('email');
        $pass = Request::input('pass');

        $loginData = DB::select('select Password from admin where email = ?', [$email]); 
     
        if (count($loginData) > 0){
            
            foreach ($loginData as $tablepass) {

                $hash = $tablepass->Password;
                $verify = password_verify($pass, $hash);

                if (($verify)){
                    return redirect('admin');
                }
                else{
                    $error='Admin Password does not match';
                    // return view('welcome')->with('error', $error);
                    return redirect()->back() ->with('alert', $error);
                }
            }
        }else{
            $error='Admin PLease Enter valid data';
            // return view('welcome')->with('error', $error);
            return redirect()->back() ->with('alert', $error);
        }
        //return redirect('login');
    }

    function view() {
        $employeeData = DB::select('select * from employees');     
        return view('employees', ["employeeData"=>$employeeData]);
    }
    function storeEmployee() {     
    
        $firstname = Request::input('fname');
        $lastname = Request::input('lname');
        $email = Request::input('email');
        $pass = Request::input('pass');
        $gender = Request::input('gender');
        $age = Request::input('age');
        $phone = Request::input('phone');

        $hash = password_hash($pass,PASSWORD_DEFAULT);


        $exist = DB::select('select * from employees where email = ? ', [$email]);
        if(count($exist) == 0){
            DB::insert('insert into employees (FName, LName, Email,Password, Age, PhoneNo, Gender) values (?, ?, ?,?, ?, ?, ?)', [$firstname , $lastname, $email,$hash, $age, $phone, $gender]);
            $msg = "Employee Added Successfully";
            return redirect('/addEmployeeForm')->with('alert', $msg);
        }else{
            $msg = "Email Already Taken";
            return redirect('/addEmployeeForm')->with('alert', $msg);
        }

		
    }
    function viewEmployee() {
        $id= request('id'); 
        $employeeData = DB::select('select * from employees where id = ?',[$id]);
        return view('edit', ["employeeData"=>$employeeData,'id'=>$id]);
    }
    function updateEmployee($id) {

        $firstname = Request::input('fname');
        $lastname = Request::input('lname');
        $email = Request::input('email');
        $pass = Request::input('pass');
        $gender = Request::input('gender');
        $age = Request::input('age');
        $phone = Request::input('phone');

        $hash = password_hash($pass,PASSWORD_DEFAULT);

        DB::update('update employees set FName=?, LName=?, Email=?, Password=?, Age=?, Gender=?, PhoneNo=? where id= ?', [$firstname, $lastname, $email,$hash, $age, $gender, $phone ,$id]);
        return redirect('employees');
    }

    function deleteEmployee() {
        $id= request('id');
        DB::delete('delete from employees where id= ?', [$id]);
        return redirect('employees');
    } 

    //Employee stuff
    function matchEmployee() {
        $email = Request::input('email');
        $pass = Request::input('pass');
        
        $employeeData = DB::select('select * from employees where email = ?', [$email]); 
        $guests = DB::select('select * from guests');
        $rooms = DB::select('select * from rooms');
        $reserved = DB::select('select * from rooms where Status!="Empty" ');
        $reservedCount = count($reserved);
        $roomsCount = count($rooms);
        $guestsCount = count($guests);

        $loginData = DB::select('select * from employees where Email = ?', [$email]); 
     
        if (count($loginData) > 0){
            
            foreach ($loginData as $tablepass) {

                $hash = $tablepass->Password;
                $verify = password_verify($pass, $hash);

                if (($verify) == $pass){
                    foreach($employeeData as $eData){
                        DB::update('update employees set Status = "online" where Email = ? ', [$email]);
                    }
                    return view('employeeHome', ["loginData"=>$loginData], ["employeeData"=>$employeeData, "guestsCount"=>$guestsCount, "roomsCount"=>$roomsCount, "reservedCount" => $reservedCount]);
                }
                else{
                    $error='Password does not match';
                    return redirect()->back() ->with('alert', $error);
                }
            }
        }else{
            $error='PLease Enter valid data';
            return redirect()->back() ->with('alert', $error);
        }
    }
    function logout(){
        DB::update('update employees set Status = "offline" where Status = "online"');
        return view('welcome');
    }

    
}
