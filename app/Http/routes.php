<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/login', function () {
    return view('Auth.login')->middleware('guest');
});

Route::post('/handlelogin', ['as' => 'handlelogin', 'uses' => 'AuthController@handleLogin']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

$router->group(['middleware' => 'guest'], function () {
    Route::get('/', function () { return view('Auth.login'); });
    Route::any('/register','RegisterController@registerform');

});


Route::group(['middleware' => ['ActionCenter']], function () {
    Route::group(['middleware' => ['auth']], function() {
        Route::any('/actioncenter/{assmun}', 'ActionCenterController@homeaction');
        Route::any('/actioncenter/{assmun}/reports', 'ActionCenterController@reports');
        Route::any('/{assmun}/appuser', 'ActionCenterController@mgausers');
        Route::any('/actioncenter/{assmun}/responders', 'ActionCenterController@responders');
        Route::any('/actioncenter/{assmun}/{department}', 'ActionCenterController@department');
        Route::any('/actioncenter/{assmun}/{department}/responders', 'ActionCenterController@responders1');
        Route::any('/{assmun}/infoblast', 'ActionCenterController@infoblast');
        Route::any('/{assmun}/upload', 'ActionCenterController@upload')->name('auth.upload');
        Route::any('/{assmun}/trackLocation/{imei}', 'TrackLocationController@trackLocation');
    });
});

Route::group(['middleware' => ['CommandCenter']], function () {

    Route::group(['middleware' => ['auth']], function() {
        Route::any('/trackLocation/{imei}', 'TrackLocationController@trackLocation');
        Route::any('/home', 'CommandCenterController@home');
        Route::any('/reports', 'CommandCenterController@reports');
        Route::any('/app_user', 'CommandCenterController@appUsers');
    });
});

Route::any('/503', function(){
    //return view('errors.503');
    return $this->view();
});

Route::any('/usernamepassword', 'ChangepassController@changepass');
//Route::any('/reports', 'ActionCenterController@reports');
