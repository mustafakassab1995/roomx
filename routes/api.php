 <?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
Route::post('sendverify','AuthController@sendverify');
Route::post('updateARoom','AuthController@updateARoom');
Route::post('addRoomWithPhotos','AuthController@addRoomWithPhotos');
Route::post('getSimilar','AuthController@getSimilar');
Route::post('rcvverify','AuthController@rcvverify');
Route::get('links','AuthController@links');
Route::get('newornot','AuthController@newornot');
Route::post('sendnotorcv','AuthController@sendnotorcv');
Route::post('acceptreservationandroid','AuthController@acceptreservationandroid');
Route::get('geo','AuthController@geo');
 Route::post('authenticate', 'AuthController@authenticate');
 Route::post('sendnotificationios', 'AuthController@sendnotificationios');
 Route::post('acceptreservation', 'AuthController@acceptreservation');
 Route::post('addphototoroom', 'AuthController@addphototoroom');
 Route::post('addphotostoroom', 'AuthController@addphotostoroom');
 Route::post('sendamsg', 'AuthController@sendamsg');
 Route::post('getaconvirsation', 'AuthController@getaconvirsation');
 Route::post('opensessionchat', 'AuthController@opensessionchat');
 Route::post('markconversationasseen', 'AuthController@markconversationasseen');
 Route::post('sendanewmsg', 'AuthController@sendanewmsg');
 Route::post('getmylastmsgs', 'AuthController@getmylastmsgs');
 Route::post('getallofmymsgs', 'AuthController@getallofmymsgs');
 Route::post('getmyconvirsations', 'AuthController@getmyconvirsations');
 Route::post('deletearoom', 'AuthController@deletearoom');
 Route::post('Usershowdata', 'AuthController@Usershowdata');
 Route::post('getuserprofile', 'AuthController@getuserprofile');
 Route::post('showreserved', 'AuthController@showreserved');
 Route::post('Register', 'AuthController@Register');
 Route::post('addroom', 'AuthController@addroom');
 Route::post('shownewestrooms', 'AuthController@shownewestrooms');
 Route::post('shownewestroomsnologin', 'AuthController@shownewestroomsnologin');
 Route::post('addtowishlist','AuthController@addtowishlist');
 Route::post('getARoom','AuthController@getARoom');
 Route::post('removefromwishlist','AuthController@removefromwishlist');
 Route::post('showwishlist','AuthController@showwishlist');
 Route::post('rate','AuthController@rate');
 Route::post('getmyrooms','AuthController@getmyrooms');
 Route::post('getmyreservations','AuthController@getmyreservations');
 Route::post('reservearoom','AuthController@reservearoom');
 Route::post('updateuserprofile','AuthController@updateuserprofile');
 Route::post('updateuserprofilepicture','AuthController@updateuserprofilepicture');
Route::post('getuserreservations','AuthController@getuserreservations');
Route::post('fucking_hosted','AuthController@fucking_hosted');
Route::post('myprofile','AuthController@myprofile');

 