<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomController extends Controller
{
    //
    public function finalGeoCoding(Request $request){


$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$request->get('location').'&sensor=false';
$cURL = curl_init();

curl_setopt($cURL, CURLOPT_URL, $url);
curl_setopt($cURL, CURLOPT_HTTPGET, true);

curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Accept: application/json'
));
// $ch = curl_init($url);
$response = curl_exec($cURL);
// $x = json_encode($response);
// echo $x['results'];
// dd($x);


}
       public function h(){
//    \Nexmo::message()->send([
//     'to' => '970592414345',
//     'from' => 'Yallabookingsystem',
//     'text' => 'Using the facade to send a mesage.'
// ]);
   $bulkSms = new \lk('azizmath', 'Lion@prince8', 'http://bulksms.vsms.net:5567');
   $text = 'اب فهد';
$encodedMessage = bin2hex(mb_convert_encoding($text, 'utf-16', 'utf-8')) ; 
$bulkSms->sendMessage('966509777938', $encodedMessage, ['dca' => '16bit']);
// $bulkSms->sendMessage('970592414345', 'Hello there!');
//        	$nexmo = app('Nexmo\Client');
// $nexmo->message()->send([
//     'to' => '970592414345',
//     'from' => 'Yallabookingsystem',
//     'text' => 'Using the instance to send a message.'
// ]);
    }
}
