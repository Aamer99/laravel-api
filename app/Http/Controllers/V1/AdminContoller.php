<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\welcomeMail;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminContoller extends Controller
{
    
    
    public function getAddEmployeeRequests()
    {
         try{ 

           $users = User::where("approved",false)->get(); 

           return response()->json(["users"=>$users],200);

         }catch(Error $err){
            return response()-> json(["message"=> $err],400);
         }
    }

    

    public function getAddEmployeeRequest(Request $request,string $user_id)
    {
        try{

        $user = User::find($user_id);
        return response()->json(['user'=> $user],200);

        } catch(Error $err){
            return response()->json(["message"=> $err],400);
        }
    }

  
    public function approvedAddEmployeeRequest(string $user_id)
    {
        try{ 
            
            $employee = User::find($user_id);
            $employee -> approved = true; 
            $employeePassword = Crypt::decrypt($employee-> password);
            $employee-> password = Hash::make($employeePassword);
            $employee -> save();
               
            Mail::to($employee-> email)->send(new welcomeMail($employee-> email,$employee-> name,$employeePassword,$employee-> type));

            return response()-> json(['messsage'=> "the employee successfully approved "],200);

        }catch(Error $err){
            return response()-> json(['message'=> $err],400);
        }
    }

   
}