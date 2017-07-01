<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;

use IlluminateHttpRequest;

use AppHttpRequests;
use AppHttpControllersController;
use JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use ClassPreloader\Config;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{ 
    //

    public function authenticate(Request $request)
    {
        if($request->get('phone'))
        $credentials = $request->only('phone', 'password');
    if($request->get('email'))
        $credentials = $request->only('email', 'password');

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials' , 'result' => 0], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        //$user = JWTAuth::parseToken()->authenticate();
         try {

            $user = JWTAuth::toUser($token);
            if($request->get('player_id')){
               \App\User::where('id',$user->id)->update(['player_id'=>$request->get('player_id')]); 
            }
            
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }

$data['token'] = $token;
$data['result'] = 1 ;
       
        return response()->json($data);
    }


public function humanTiming ($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}
 public function getuserprofile(Request $request){
   $user = \App\User::find($request->input('user_id'));

            return response()->json(compact('user'));  
    }
    public function rcvverify(Request $request){
        $h =  $request->get('number');
  $value = \Cache::get("num_$h");
  $code = $request->get("code");
if($value==$code){
return response()->json(['result'=>1 , 'msj'=>'الرمز صحيح']);

}
else{
return response()->json(['result'=>0 , 'msj'=>'الرمز خطأ']);

}
    }

//     public function sendverify(Request $request){
//         $x =  rand(1001, 9000);
//        // echo $x.'<br>';
//      $h =  $request->get('number');
//   $value = \Cache::put("num_$h",$x,60);

//          $bulkSms = new \lk('azizmath', 'Lion@prince8', 'http://bulksms.vsms.net:5567');
// $text = 'كود التحقق : '.$x;
// $encodedMessage = bin2hex(mb_convert_encoding($text, 'utf-16', 'utf-8')) ; 
// $bulkSms->sendMessage($request->get('number'), $encodedMessage, ['dca' => '16bit']);


//     }
public function getmyconvirsations(Request $request){

 $token = $request->get('token');
        try {

            $user = JWTAuth::toUser($token);
            $convirsations = \App\Chatpeople::where('user1_id','=',$user->id)
            ->orWhere('user2_id','=',$user->id)->with('user')->with('chatmsg')->distinct()->orderBy('id', 'desc')->get();
            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json($convirsations);  


}
public function getallofmymsgs(Request $request){

 $token = $request->get('token');
        try {

            $user = JWTAuth::toUser($token);
            $msgs = \App\Chatmsg::where('rcv_id','=',$user->id)
            ->orWhere('sdr_id','=',$user->id)->distinct()->orderBy('id', 'desc')->get();
            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json($msgs);  


}
public function getmylastmsgs(Request $request){

 $token = $request->get('token');
        try {

            $user = JWTAuth::toUser($token);
            $msgs = \App\Chatmsg::where('seen','=',0)->where('rcv_id','=',$user->id)
            ->orWhere('sdr_id','=',$user->id)->distinct()->orderBy('id', 'desc')->get();
            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json($msgs);  


}
public function markconversationasseen(Request $request){

 $token = $request->get('token');
        try {

            $user = JWTAuth::toUser($token);
            
          \App\Chatmsg::where('chatpeople_id','=',$request->get('chatpeople_id'))->where('rcv_id','=',$user->id)->update(['seen'=>1]);
           

            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(['result'=>1]);  


}
public function sendanewmsg(Request $request){

 $token = $request->get('token');
        try {

            $user = JWTAuth::toUser($token);
            $sata['user1_id']=$user->id;
            $sata['user2_id']=$request->get('rcv_id');

           $instance =  \App\Chatpeople::create($sata);
           $data['rcv_id']=$request->get('rcv_id');
            $data['sdr_id']=$user->id;
            $data['chatpeople_id'] = $instance->id ;
            $data['msg']=$request->get('msg');
            \App\Chatmsg::create($data);

            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(['msg'=>'تم الارسال','result'=>1]);  


}
public function sendnotification($player_id , $msg){
   $content = array(
            "en" => $msg // message
            );

        $fields = array(
            'app_id' => "b6c15330-adfd-4b90-a5a6-1368f2ab7b60", // app id
            'include_player_ids' => [$player_id], // tokens
            'data' => array("extra_data" => 'test'), // extra data
            'contents' => $content,
            'ios_badgeType'   => 'Increase',
            'ios_badgeCount'  =>  1
        );

        $fields = json_encode($fields);
       

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic ZWUwYTEyY2EtOGU5Yi00YjEzLWJmMTctYTM2MGNiZWFkYzFi'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
         return response()->json(['result'=>1]);

}
public function sendamsg(Request $request){

        $content = array(
            "en" => $request->get('msg') // message
            );

        $fields = array(
            'app_id' => "b6c15330-adfd-4b90-a5a6-1368f2ab7b60", // app id
            'include_player_ids' => [$request->get('player_id')], // tokens
            'data' => array("extra_data" => 'test'), // extra data
            'contents' => $content,
            'ios_badgeType'   => 'Increase',
            'ios_badgeCount'  =>  1
        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic ZWUwYTEyY2EtOGU5Yi00YjEzLWJmMTctYTM2MGNiZWFkYzFi'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

         $response;
    

    
    $return["allresponses"] = $response;
    $return = json_encode( $return);

    print("\n\nJSON received:\n");
    print($return);
    print("\n");


// \OneSignal::sendNotificationToUser($request->get('msg'), $request->get('user_id'));
// return response()->json(['msg'=>'تم']);

 // $token = $request->get('token');
 //        try {

 //            $user = JWTAuth::toUser($token);
            
 //           $data['rcv_id']=$request->get('rcv_id');
 //            $data['sdr_id']=$user->id;
 //            $data['chatpeople_id'] = $request->get('chatpeople_id') ;
 //            $data['msg']=$request->get('msg');
 //            \App\Chatmsg::create($data);

 //            //Check if barcode is valid
 //            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
 //        } catch (Exception $e) {

 //            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

 //                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

 //            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

 //                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

 //            }else{

 //                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

 //            }

 //        }
 //        return response()->json(['msg'=>'تم الارسال','result'=>1]);  


}
 public function myprofile(Request $request){
         

       $token = $request->get('token');
        try {

            $user = JWTAuth::toUser($token);
            
            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(compact('user'));  
    }
    public function updateuserprofilepicture(Request $request){
         

       $token = $request->get('token');
        try {

            $user = JWTAuth::toUser($token);
            
            $validator = \Validator::make($request->all(),[
    'photolink' => 'required|mimes:jpeg,bmp,png',
            
    ]);

                if ($validator->fails())
                 {    
                    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);}
                     $file= $request->file('photolink');
                    if($file){
                        $photolink = time().'.'.$file->getClientOriginalExtension();
                    $request->file('photolink')->move(public_path('images'), $photolink);
                    $photolink = asset('images/'.$photolink);
                    $data['photolink'] = $photolink;
                    }
          
                  \App\User::where('id', $user->id)
                         ->update($data);
                  $user = \App\User::find($user->id);       
            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(['result'=>1,'user'=>$user]);  
    }

    public function updateuserprofile(Request $request){
        
       $token = $request->get('token');
        try {

            $user = JWTAuth::toUser($token);
            
            $validator = \Validator::make($request->all(),[
    'name' => 'required|max:100',
            'dob'=>'date', 
             'city'=>'string|max:20',
              'country'=>'string|max:50'
    ]);

                if ($validator->fails())
                 {    
                    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
                }
            $data['name'] = $request->input('name');
            $data['dob'] = $request->input('dob');
            $data['city'] = $request->input('city');
            $data['country'] = $request->input('country');
                  \App\User::where('id', $user->id)
                         ->update($data);
                  $user = \App\User::find($user->id);       
            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(compact('user'));  
    }

    
    


 public function Usershowdata(Request $request){
       $token = $request->get('token');
       
        try {

            $user = JWTAuth::toUser($token);
            

            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(compact('user'));  
    }




public function Register(Request $request){
    

    
   
 $validator = \Validator::make($request->all(),[
    'name' => 'required|max:100',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:3|confirmed',
            'phone'=>'unique:users|regex:/(05)[0-9]{8}/'
    ]);

if ($validator->fails())
 {    
    $data['msg'] = [ 'error'=>$validator->messages(),'result'=>0];
    $data['result'] = 0 ;

    return response()->json($data,400);
}


 $data = $request->all();
      \App\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            

        ]);
       return response()->json(['msg'=>'تم التسجيل , قم بتسجيل الدخول' , 'result'=> 1],200);
        //return response()->json(compact('errors'));


}

public function shownewestroomsnologin(Request $request){
   
       
        

           
            
           
             
           $roomx = \App\Room::where('id','>','0')->where('isDeleted','<>',1)->orderBy('id','desc')->with('user')->with('room_photo')->with('room_reservation')->with('room_rating')->get();

           for ($i=0; $i < count($roomx); $i++) { 
              // $lo = $roomx[$i]->location;
              // $lo = explode(",", $lo);
              // $roomx[$i]['city']= $this->geo($lo[0],$lo[1]);
              // $roomx[$i]->update(['city'=>$this->geo($lo[0],$lo[1])]);
             // $time = strtotime($roomx[$i]['created_at']);


             // $roomx[$i]['created_at'] = $this->humanTiming($time).' ago';
              $roomx[$i]['isFavourate'] = 0;
            }
           
        

        return response()->json($roomx); 

}
public function shownewestrooms(Request $request){
    $token = $request->get('token');
       
        try {

            $user = JWTAuth::toUser($token);
            
           $roomx = \App\Room::where('id','>','0')->where('isDeleted','<>',1)->orderBy('id','desc')->with('user')->with('room_photo')->with('room_reservation')->with('room_rating')->get();
            $isFavourate =[];
           for ($i=0; $i < count($roomx); $i++) { 
              $inc = \App\Like::where('room_id','=',$roomx[$i]->id)->where('user_id','=',$user->id)->first();
              // $lo = $roomx[$i]->location;
              // $lo = explode(",", $lo);
              // $roomx[$i]['city']= $this->geo($lo[0],$lo[1]);
              if($inc){
                $roomx[$i]['isFavourate'] = 1 ;
              }
              else{
                $roomx[$i]['isFavourate'] = 0;
              }
            }
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }

        return response()->json($roomx); 

}
public function links(){
    $links = \DB::table('links')->first();
                return response()->json($links);

}
public function sendnotorcv(Request $request){
 $token = $request->get('token');
       
        try {

            $user = JWTAuth::toUser($token);
            $sdr_id= $user->id;
            $rcv_id = $request->get('rcv_id');
           $rcv=\App\User::find($rcv_id);
            $this->sendnotification($rcv->player_id , $user->name." : ".$request->get('msg'));

           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(['result'=>1]); 
}
public function geo($f , $s){
    
    $x = app('geocoder')->reverse($f,$s)->dump('geojson');;
    $x =json_decode($x->first());
    $x = $x->properties->adminLevels->{1}->name;

    return $x;
}
public function showreserved(Request $request){
   
 $token = $request->get('token');
       
        try {

            $user = JWTAuth::toUser($token);
            $id = $request->get('room_id');
           $roomx = \App\RoomReservation::where('room_id','=',$id)->get();

           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json($roomx); 
}
public function getSimilar(Request $request){
   
       
      try {
       
            $validator = \Validator::make($request->all(),[
    'room_id' => 'required|exists:rooms,id',
           
            
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
           
           $inc =  \App\Room::where('id',$request->get('room_id'))->first();
           $GLOBALS['inc'] = $inc ;
           $roomx =  \App\Room::where('id','<>',$request->get('room_id'))->where('isDeleted','<>',1)
           ->where(function ($query ){
            $inc =  $GLOBALS['inc'];
            $query->where('number_of_beds',$inc->number_of_beds)
            ->orWhere('number_of_guests',$inc->number_of_guests)
            ->orWhere('number_of_baths',$inc->number_of_baths)
            ->orWhere('number_of_rooms',$inc->number_of_rooms)
            ->orWhere('number_of_rooms',$inc->number_of_rooms)
            ->orWhere('city','like',$inc->city);
           })->orderBy('id','desc')->with('user')->with('room_photo')->with('room_reservation')->with('room_rating')->get();

           // where('id','<>',$request->get('room_id'))->where('isDeleted','<>',1)->where('number_of_beds',$inc->number_of_beds)->orWhere('number_of_guests',$inc->number_of_guests)->orWhere('number_of_baths',$inc->number_of_baths)->orWhere('number_of_rooms',$inc->number_of_rooms)->orWhere('number_of_rooms',$inc->number_of_rooms)->orWhere('city','like',$inc->city)->orderBy('id','desc')->with('user')->with('room_photo')->with('room_reservation')->with('room_rating')->get();
           for ($i=0; $i <count($roomx) ; $i++) { 
            $roomx[$i]['parent_id']=$request->get('room_id');

            for ($j=0; $j < count($roomx[$i]['room_photo']) ; $j++) { 
            $roomx[$i]['room_photo'][$j]['parent_id']=$request->get('room_id');


           }

           }

        } catch (Exception $e) {

           

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            

        }
     



 return response()->json(['roomx'=>$roomx ,'result'=>1],200);


}
public function deletearoom(Request $request){
   $token = $request->get('token');
       
      try {
        $user = JWTAuth::toUser($token);
            $validator = \Validator::make($request->all(),[
    'room_id' => 'required|exists:rooms,id',
          'delete'=>'required',   
            
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
           
            \App\Room::where('id',$request->get('room_id'))->update(['isDeleted'=>$request->get('delete')]);

        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
     



 return response()->json(['result'=>1],200);


}
public function addphotostoroom(Request $request){
   $token = $request->get('token');
       
      try {
            $validator = \Validator::make($request->all(),[
    'room_id' => 'required|exists:rooms,id',
             
              'delete'=>'boolean'
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
           if($request->get('delete')){
            \App\RoomPhoto::where('room_id','=',$request->get('room_id'))->delete();
            \App\Room::destroy($request->get('room_id'));
           }
            \App\RoomPhoto::where('room_id','=',$request->get('room_id'))->where('photolink','like','http://roomxapi.com/gaithapi/public/images/1490306455.jpeg')->delete();
          
       $files= $request->file('photolink');
                    foreach ($files as $file ) {
                        
                    

                        $photolink = rand().time().'.'.$file->getClientOriginalExtension();
                    $file->move(public_path('images'), $photolink);
                    $photolink = asset('images/'.$photolink);
                   $f['photolink'] = $photolink;
                   $f['room_id'] = $request->get('room_id');
                   \App\RoomPhoto::create($f);
                    }   

         \Cache::put('newornot', 'yes', 60);
         
                    
                   


          

            
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
     



 return response()->json(['result'=>1],200);


}
public function addphototoroom(Request $request){
   $token = $request->get('token');
       
      try {
            $validator = \Validator::make($request->all(),[
    'room_id' => 'required|exists:rooms,id',
             'photolink'=>'mimes:jpeg,bmp,png',
              'delete'=>'boolean'
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
           if($request->get('delete')){
            \App\RoomPhoto::where('room_id','=',$request->get('room_id'))->delete();
            \App\Room::destroy($request->get('room_id'));
           }
           \App\RoomPhoto::where('room_id','=',$request->get('room_id'))->where('photolink','like','http://roomxapi.com/gaithapi/public/images/1490306455.jpeg')->delete();
       $file= $request->file('photolink');
                    if($file){
                        $photolink = rand().time().'.'.$file->getClientOriginalExtension();
                    $request->file('photolink')->move(public_path('images'), $photolink);
                    $photolink = asset('images/'.$photolink);
                   $f['photolink'] = $photolink;
                   $f['room_id'] = $request->get('room_id');
                   \App\RoomPhoto::create($f);
                    }   

         \Cache::put('newornot', 'yes', 60);
         
                    
                   


          

            
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
     



 return response()->json(['result'=>1],200);


}
public function newornot(){
    $value = \Cache::get('newornot');
    \Cache::put('newornot', 'no', 60);
    if($value=='yes'){
        $flag=$value;
    
    return response()->json(['new'=>$flag]);
    }
    else{
        $flag=$value;
    return response()->json(['new'=>$flag]);

    }
}
public function finalGeoCoding($location){


// $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='$location'&sensor=false'
// $ch = curl_init($url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $response = curl_exec($ch);



}
public function addRoomWithPhotos(Request $request){
   $token = $request->get('token');
        try {
            $validator = \Validator::make($request->all(),[
    'name' => 'required|max:100',
             'number_of_guests'=>'required|numeric',
              'number_of_beds'=>'required|numeric',
               'number_of_baths'=>'required|numeric',
                'number_of_rooms'=>'required|numeric',
                 'location'=>'required',
                  'description'=>'required|min:0',
                   'tv'=>'required',
                   'price'=>'required|numeric',
                    'wifi'=>'required',
                     'pool'=>'required',
                      'air_condition'=>'required',
                       'kitchen'=>'required',
'city'=>'required'
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
            $user = JWTAuth::toUser($token);
            $data = $request->all();
          
           $data['user_id'] = $user->id;

          $roominc =  \App\Room::create($data);
          $f['room_id'] = $roominc->id;
          

         $files= $request->file('photolink');
         if(empty($files)){
            \App\RoomPhoto::create($f);
           
         }
         else{  foreach ($files as $file ) {
                        
                    

                        $photolink = rand().time().'.'.$file->getClientOriginalExtension();
                    $file->move(public_path('images'), $photolink);
                    $photolink = asset('images/'.$photolink);
                   $f['photolink'] = $photolink;
                   $f['room_id'] = $f['room_id'];
                   \App\RoomPhoto::create($f);
                    } }
                    

         \Cache::put('newornot', 'yes', 60);
          $roominc= \App\Room::where('id',$f['room_id'])->with('user')->with('room_photo')->with('room_reservation')->with('room_rating')->first();

           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
     



 return response()->json(['room'=>$roominc,'result'=>1],200);


}
public function addroom(Request $request){
   $token = $request->get('token');
       
        try {
            $validator = \Validator::make($request->all(),[
    'name' => 'required|max:100',
             'number_of_guests'=>'required|numeric',
              'number_of_beds'=>'required|numeric',
               'number_of_baths'=>'required|numeric',
                'number_of_rooms'=>'required|numeric',
                 'location'=>'required',
                  'description'=>'required|min:0',
                   'tv'=>'required',
                   'price'=>'required|numeric',
                    'wifi'=>'required',
                     'pool'=>'required',
                      'air_condition'=>'required',
                       'kitchen'=>'required',
'city'=>'required'
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
            $user = JWTAuth::toUser($token);
            $data = $request->all();
          
           $data['user_id'] = $user->id;

          $roominc =  \App\Room::create($data);
          $f['room_id'] = $roominc->id;
          \App\RoomPhoto::create($f);

         //   $lo = $request->get('location');
         //      $lo = explode(",", $lo);
         //   //    $likedrooms[$i]['city']= $this->geo($lo[0],$lo[1]);
         // \App\Room::where('id',$roominc->id)->update(['city'=>$this->geo($lo[0],$lo[1])]); 

            
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
     



 return response()->json(['room_id'=>$roominc->id,'result'=>1],200);


}
public function updateARoom(Request $request){
   $token = $request->get('token');
       
        try {
            $validator = \Validator::make($request->all(),[
    'name' => 'required|max:100',
             'number_of_guests'=>'required|numeric',
              'number_of_beds'=>'required|numeric',
               'number_of_baths'=>'required|numeric',
                'number_of_rooms'=>'required|numeric',
                 'location'=>'required',
                  'description'=>'required|min:0',
                   'tv'=>'required',
                   'price'=>'required|numeric',
                    'wifi'=>'required',
                     'pool'=>'required',
                      'air_condition'=>'required',
                       'kitchen'=>'required',
'city'=>'required',
'room_id'=>'required|exists:rooms,id'
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
            $user = JWTAuth::toUser($token);
            $data = $request->except(['token','room_id']);
          
           

           \App\Room::where('id',$request->get('room_id'))->where('user_id',$user->id)->update($data);
          $roominc= \App\Room::where('id',$request->get('room_id'))->with('user')->with('room_photo')->with('room_reservation')->with('room_rating')->first();
          

         
            
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
     



 return response()->json(['room'=>$roominc,'result'=>1],200);


}

public function searchARoom(Request $request){
    
            $data=[];
                if($request->city){
                   $data['rooms']= \App\Room::where('city','like',$request->get('city'))->with('user')->with('room_photo')->get();
                }
                if($request->tv){
                      $GLOBALS['tv']=$request->tv;
                      $GLOBALS['wifi']=$request->wifi;
                      $GLOBALS['pool']=$request->pool;
                      $GLOBALS['kitchen']=$request->kitchen;
                      $GLOBALS['air_condition']=$request->air_condition;
                     $data['rooms']=  \App\Room::where('id','>',0)->where('isDeleted','<>',1)
           ->where(function ($query ){
            
            $query->where('wifi',$GLOBALS['wifi'])
            ->orWhere('tv',$GLOBALS['tv'])
            ->orWhere('pool',$GLOBALS['pool'])
            ->orWhere('air_condition',$GLOBALS['air_condition'])
            ->orWhere('kitchen',$GLOBALS['kitchen']);
           })->orderBy('id','desc')->with('user')->with('room_photo')->with('room_reservation')->with('room_rating')->get();
                }

return response()->json($data);
}
public function getARoom(Request $request){
     $validator = \Validator::make($request->all(),[
    'room_id' => 'required|exists:rooms,id',
            
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}

$room = \App\Room::where('id','=',$request->get('room_id'))->with('user')->with('room_photo')->first();
return response()->json($room);
}
public function getaconvirsation(Request $request){

 $token = $request->get('token');
        try {

            $user = JWTAuth::toUser($token);
            $msgs = \App\Chatmsg::where('chatpeople_id','=',$request->get('chatpeople_id'))
            ->orderBy('id', 'desc')->get();
            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json($msgs);  


}
   public function addtowishlist(Request $request){
     
    
       $token = $request->get('token');
         
        try {
            $user = JWTAuth::toUser($token);
            $validator = \Validator::make($request->all(),[
    'room_id' => 'required|exists:rooms,id',
          
            
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
         $like = \App\Like::where('user_id','=',$user->id)->where('room_id','=',$request->get('room_id'))->first();
         if($like){
            $like->delete();
            return response()->json(['msj'=>'تم ازالة اللايك' , 'result'=>'0']);
         }
         else{
            $data['user_id']  = $user->id ;
           $data['room_id']  =  $request->input('room_id');
           \App\Like::create($data);
           return response()->json(['msj'=>'تم الإضافة إلى قائمة الإعجابات' , 'result'=>'1']);
         }
           
           

            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(['msj'=>'لم يحدث شيء']);  
    }
 public function showwishlist(Request $request){
     
    
       $token = $request->get('token');
         
        try {
            $user = JWTAuth::toUser($token);
           
          
           
          $likes = $user->like->all();
          $likedrooms = [];
          for ($i=0; $i < count($likes) ; $i++) { 
           $likedrooms[$i] = \App\Room::where('id','=',$likes[$i]->room_id)
           ->with('user')->with('room_photo')->with('room_reservation')->first();
            
           
              $inc = \App\Like::where('room_id','=',$likes[$i]->room_id)->where('user_id','=',$user->id)->first();
              // $lo = $likedrooms[$i]->location;
              // $lo = explode(",", $lo);
              // $likedrooms[$i]['city']= $this->geo($lo[0],$lo[1]);
              if($inc){
                $likedrooms[$i]['isFavourate'] = 1 ;
              }
              else{
                $likedrooms[$i]['isFavourate'] = 0;
              }
            
          }
            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json($likedrooms);  
    }
public function removefromwishlist(Request $request){

  $token = $request->get('token');
         
        try {
            $user = JWTAuth::toUser($token);
            $validator = \Validator::make($request->all(),[
    
       'room_id'=>'required|exists:rooms,id',  
            
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
          $like = \App\Like::where('user_id','=',$user->id)->where('room_id','=',$request->get('room_id'))->first();
         if($like){
            $like->delete();
            return response()->json(['msj'=>'تم ازالة اللايك','result'=>1]);
         }
          else{
             return response()->json(['msj'=>'تم الإضافة إلى قائمة الإعجابات','result'=>1]);
          }
           // $like_id =  $request->input('id');
           // \App\Like::destroy($like_id);

            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(['msj'=>'تم الحذف من قائمة التفضيلات','result'=>1]);  

}
public function acceptreservationandroid(Request $request){
 $token = $request->get('token');
         
        try {
            $user = JWTAuth::toUser($token);
            $validator = \Validator::make($request->all(),[
    
       'reservation_id'=>'required|exists:room_reservations,id',  
       'isAccepted'=>'required|boolean'
            
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
$instance = \App\RoomReservation::where('id','=',$request->get('reservation_id'))->first();
$msg = "none";
if($request->get('isAccepted')==1){
        \App\RoomReservation::where('id','=',$request->get('reservation_id'))->update(['isAccepted'=>1]);
       $msg = "لقد قمت بقبول الحجز";
}
else{
        \App\RoomReservation::where('id','=',$request->get('reservation_id'))->delete();
       $msg = "لقد قمت برفض الحجز";
        


}
           // $like_id =  $request->input('id');
           // \App\Like::destroy($like_id);

            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(['msj'=>$msg,'result'=>1 ,'isAccepted'=>$request->get('isAccepted')]);  
  
}
public function acceptreservation(Request $request){
 $token = $request->get('token');
         
        try {
            $user = JWTAuth::toUser($token);
            $validator = \Validator::make($request->all(),[
    
       'reservation_id'=>'required|exists:room_reservations,id',  
       'isAccepted'=>'required|boolean'
            
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
$instance = \App\RoomReservation::where('id','=',$request->get('reservation_id'))->first();
$user = \App\User::find($instance->user_id);
$flag=false;
if($user->player_id){
    $flag=true;
}
if($request->get('isAccepted')==1){
        \App\RoomReservation::where('id','=',$request->get('reservation_id'))->update(['isAccepted'=>1]);
        if ($flag) {

        $this->sendnotification($user->player_id , "تم قبول حجزك");
           
        }
}
else{
        \App\RoomReservation::where('id','=',$request->get('reservation_id'))->delete();
         if ($flag) {
        $this->sendnotification($user->player_id , "تم رفض حجزك");
    }


}
           // $like_id =  $request->input('id');
           // \App\Like::destroy($like_id);

            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(['msj'=>'تم','result'=>1]);  
  
}
// public function getroomrating(Request $request){
//     $token = $request->get('token');
         
//         try {
//             $user = JWTAuth::toUser($token);
//             $validator = \Validator::make($request->all(),[
//      'room_id' => 'required|exists:rooms,id',
       
//     ]);

// if ($validator->fails()) {    
//     return response()->json(['result'=>0 , 'error'=>$validator->messages(),'result'=>0],400);
// }
          
          
        
//             //Check if barcode is valid
//             //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
//         } catch (Exception $e) {

//             if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

//                 return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

//             }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

//                 return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

//             }else{

//                 return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

//             }

//         }
//         return response()->json(['msj'=>'تم التقييم , شكرا لك !','result'=>1]);  
// }

public function rate(Request $request){

  $token = $request->get('token');
         
        try {
            $user = JWTAuth::toUser($token);
            $validator = \Validator::make($request->all(),[
     'room_id' => 'required|exists:rooms,id',
       'rating'=>'required|numeric',   
            'comment'=>'required'
    ]);

if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
          
          
          $data['user_id']  = $user->id ;
           $data['room_id']  =  $request->input('room_id');
           $room_id  =  $request->input('room_id');
            $data['rating']  =  $request->input('rating');
            $data['comment'] = $request->input('comment');
           \App\RoomRating::create($data);

         $rooms =   \App\RoomRating::where('room_id','=',$room_id)->get();
         $numberofusersrating1 = 0;
         $numberofusersrating2 = 0;
         $numberofusersrating3 = 0;
         $numberofusersrating4 = 0;
         $numberofusersrating5 = 0;
         $numberofratings = 0;
         foreach ($rooms as $room) {
             $ratingfirst = $room -> rating ;
             if($ratingfirst== '1'){
                $numberofratings++;
                $numberofusersrating1++;
             }
             if($ratingfirst=='2'){
                $numberofratings++;
                $numberofusersrating2++;
             }
             if($ratingfirst=='3'){
                $numberofratings++;
                
                $numberofusersrating3++;
             }
             if($ratingfirst=='4'){
                $numberofratings++;

                $numberofusersrating4++;
             }
             if($ratingfirst=='5'){
                $numberofratings++;

                $numberofusersrating5++;
             }

         }
                
         $rating = ((5*$numberofusersrating5)+(4*$numberofusersrating4)+(3*$numberofusersrating3)+(2*$numberofusersrating2)+(1*$numberofusersrating1))/$numberofratings ;
         $room = \App\Room::find($room_id);

$room->rating = $rating;

$room->save();
            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(['msj'=>'تم التقييم , شكرا لك !','result'=>1]);  

}


   public function reservearoom(Request $request){
     
    
       $token = $request->get('token');
         
        try {
            $user = JWTAuth::toUser($token);
            $validator = \Validator::make($request->all(),[
    'room_id' => 'required|exists:rooms,id',
      'start' => 'required|date|after:'.date('Y/m/d',strtotime("-1 days")),
      'end' => 'required|date|after:'.date('Y/m/d',strtotime("-1 days"))
            
    ]);
if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
$room_id = $request->get('room_id');
$start = $request->get('start');
$end = $request->get('end');
$reservation = \App\RoomReservation::where('room_id','=',$room_id)->where('start' ,'<=' , $start)->
where('end','>=',$end)->where('isAccepted','=','1')->first();
// if(!empty($reservation)||count($reservation)>0){
//     return response()->json(['result'=>0 , 'error'=>'يوجد حجز في هئا التاريخ','result'=>0],400);
// }
          
           $data['user_id']  = $user->id ;
           $data['room_id']  =  $request->input('room_id');
           $data['start']  =  $request->input('start');
           $data['end']  =  $request->input('end');
           \App\RoomReservation::create($data);

           $ii = \App\Room::find($data['room_id']);
           $ouser= \App\User::find($ii->user_id);
           if($ouser->player_id){
   $this->sendnotification($ouser->player_id , "تم تقديم طلب لحجز غرفة خاصة بك");
   $this->getresult();
}
            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(['msj'=>'تم حجز الغرفة , شكرا لك !','result'=>1,'reservation'=>$data]);  
    }
    public function reportARoom(Request $request){
     
    
       $token = $request->get('token');
         
        try {
            $user = JWTAuth::toUser($token);
            $validator = \Validator::make($request->all(),[
    'room_id' => 'required|exists:rooms,id',
     
            
    ]);
if ($validator->fails()) {    
    return response()->json([ 'error'=>$validator->messages(),'result'=>0],400);
}
    \DB::table('reports')->insert(['room_id'=>$request->get('room_id')]);

            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(['result'=>1,'reservation'=>$data]);  
    }
public function getresult(){
    return response()->json(['msj'=>'تم حجز الغرفة , شكرا لك !','result'=>1]);
}
 public function getuserreservations(Request $request){
     
    
       $token = $request->get('token');
         
        try {
            $user = JWTAuth::toUser($token);
           


          
          
         $reservation= \App\RoomReservation::where('user_id' , '=' , $user->id)->where('end','>',date('Y/m/d',strtotime("-1 days")))->with('room')->orderBy('id','desc')->get();

            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(compact('reservation'));  
    }

public function getmyreservations(Request $request){
     
    
       $token = $request->get('token');
         
        try {
            $user = JWTAuth::toUser($token);
           
            $room_ids=\App\Room::where('user_id','=' ,$user->id)->get();
            if(empty($room_ids)||count($room_ids)==0){
                 return response()->json(['msg'=>'لا يوجد غرف بإسمك','result'=>0]); 
            }
            for ($i=0; $i <count($room_ids) ; $i++) { 
                 $room_idd[] = $room_ids[$i]->id;
             }
              
          //return response()->json($room_id);
          for ($i=0; $i <count($room_idd) ; $i++) { 
             $sh= \App\RoomReservation::where('room_id' , '=' , $room_idd[$i])->where('isAccepted','=','0')->where('end','>',date('Y/m/d',strtotime("-1 days")))->with('user')->with('room')->orderBy('id','desc')->first();
             if($sh){
                $reservation[]=$sh;
             }
          }
         // $reservation= \App\RoomReservation::whereIn('room_id' , '=' , $room_idd)->with('user')->with('room')->orderBy('id','desc')->get();

            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json(compact('reservation'));  
    }



 
public function fucking_hosted(Request $request){
     
    
       $token = $request->get('token');
         
        try {
            $user = JWTAuth::toUser($token);
           

            $rooms = \App\Room::where('id','>','0')->orderBy('id','desc')->get();
            foreach ($rooms as $room) {
               $users[] = \App\User::where('id','=',$room->user_id)->get();
            }
            
            $roomx['users'] = $users ;


            //Check if barcode is valid
            //if it is not valid, then "abort(422, 'Barcode is not valid')"
           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json($roomx);  
    }
    public function getmyrooms(Request $request){
     $token = $request->get('token');
         
        try {
            $user = JWTAuth::toUser($token);
           
            $rooms=\App\Room::where('user_id','=' ,$user->id)->with('room_photo')->get();
            
              
          //return response()->json($room_id);
        $x = count($rooms);
         if($x==0){
          return response()->json(['result'=>0]);

      } 

           
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['result'=>0 , 'error'=>'Token is Invalid']);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['result'=>0 , 'error'=>'انتهت الجلسة , يرجى تسجيل الدخول']);

            }else{

                return response()->json(['result'=>0 , 'error'=>'Something is wrong']);

            }

        }
        return response()->json($rooms); 
}
    public function sendnotificationios(){
        $notification= 'msg';
        

        //\Notification::send(\App\User::find(24), new \App\Notifications\reservation($notification));
    }


}

