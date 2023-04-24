<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\sendedRequestMail;
use App\Models\Request as UserRequest;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\Mail;

class UserRequestsController extends Controller
{
    
    public function getOneRequest(string $request_id){
        try{

            $request = UserRequest::find($request_id);

            if($request){

             return response()-> json(["request"=> $request],200);

            } else{

                return response()-> json(["message"=> "not found"],404);
            }

        }catch(Error $err){

            return response()-> json(["message"=> $err],400);
        }
    }

   
    public function approvedRequest(string $request_id){
        try{

            $request = UserRequest::find($request_id); 

            if($request){

              $request-> status = 1; 
              $request-> save();

              return response()-> json(["message"=> "the requests approved successfully "],200);

            } else{

                return response()-> json(["message"=> "the request not found"],404);
            }

        }catch(Error $err){

            return response()-> json(["message"=> $err],400);
        }
    }

    public function denyRequest(string $request_id){
        try{

            $request = UserRequest::find($request_id);
            if($request){ 
            $request-> delete();
            

            return response()->json(["message"=>" the request denied successfully "],200);
            } else{

                return response()->json(["message"=> "the request not found"],404);
            }

        }catch(Error $err){
            return response()-> json(["message"=> $err],400);
        }
    }

    public function getAllRequests(string $user_id){
        try{

            $user = User::find($user_id);

            if($user){

                if($user-> type == 0){
                    $requests = UserRequest::where("type",1)->get(); 

                    return response()->json(["requests"=> $requests],200);

                } else{

                    $requests = UserRequest::where(["department_id"=>$user-> department_id,"type"=> 2])-> get();
                    return response()->json(["requests"=> $requests],200);
                }



            } else {
                return response()-> json(["message"=> "the user not exist"],404);
            }


        }catch(Error $err){
            return response()-> json(["message"=> $err],400);
        }

    }

    public function getUserRequests(string $user_id){
        try{

            $request = UserRequest::where("owner_id",$user_id)->get(); 

            return response()-> json(["requests"=> $request]);

        }catch(Error $err){
            return response()-> json(["messsage"=> $err],400);
        }
    }

    public function createRequest(Request $request,string $user_id){
        try{

            $user = User::find($user_id);
 
            if($user){

                $request_number = $user-> type == 1 ? 'M-' : "E-";
                $newRequest = new UserRequest(); 
                $newRequest-> file_id = $request-> file_id;
                $newRequest-> owner_id = $user-> id; 
                $newRequest-> department_id = $user-> department_id;
                $newRequest-> type = $user-> type; 
                $newRequest-> status = 0;
                $newRequest-> request_number = $request_number .sprintf("%06d", mt_rand(1, 9999999));
                $newRequest-> description = $request-> description; 
                $newRequest-> save(); 
                  $date = date("l d F Y");
                 Mail::to($user-> email)->send(new sendedRequestMail($newRequest-> request_number,$date));

              return response()-> json(["message"=> "the request send successfully"],200);

            } else{
                
                return response()-> json(["message"=> "sorry the user not exist "],400);
            }

        }catch(Error $err){
            return response()-> json(["message"=> $err],400);
        }
    }
}
