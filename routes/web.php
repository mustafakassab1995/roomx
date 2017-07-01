<?php



use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
 Route::get('Test', 'RoomController@h');
 Route::get('fg', 'RoomController@finalGeoCoding');
Route::get('sendnotificationios','AuthController@sendnotificationios');
Route::get('sendverify','AuthController@sendverify');
Route::get('rcvverify','AuthController@rcvverify');

Route::get('/support', function () {
    return view('support');
});

Route::post('/supportaction', function(Request $request) {
	  $validator = \Validator::make($request->all(),[
    'email' => 'required|email',
             'msg'=>'required',
              
    ]);

if ($validator->fails()) {    
    return response()->json(['error'=>$validator->messages()],400);
}
$data['email']=$request->get('email');
$data['msg']=$request->get('msg');
   \Mail::send('emails.support', $data, function ($message) {
    $message->from('roomxapi@roomxapi.com', 'support');

    $message->to('support@roomxapi.com');
});
    return "تم ارسال الرسالة للدعم , نرجو منك انتظار الرد";
});

Auth::routes();

Route::get('/home', 'HomeController@index');
