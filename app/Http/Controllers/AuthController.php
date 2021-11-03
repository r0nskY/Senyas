<?php
/**
 * Created by PhpStorm.
 * User: ADSOPH CROWN
 * Date: 5/29/2017
 * Time: 1:04 PM
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{

    public function handleLogin(Request $request) {
        $data = $request->only('username', 'password');
        $user = User::where('username', $request->get('username'))->first();
        if(\Auth::attempt($data)) {

            if (\Auth::user()->user_type == "1") {
                \Auth::login($user);
                return redirect('/home');

            } elseif (\Auth::user()->user_type == "0") {
                \Auth::login($user);
                $ass_mun = \Auth::user()->ass_mun;
                return redirect("/actioncenter/$ass_mun");
            }elseif (\Auth::user()->user_type == "2") {
                \Auth::login($user);
                $ass_mun = \Auth::user()->ass_mun;
                $dep = \Auth::user()->position;
                return redirect("/actioncenter/$ass_mun/$dep");
            }else {
                return back()->withInput()->withErrors(['email' => 'Page Restricted. Please contact the administrator.']);
            }
        }
        return back()->withInput()->withErrors(['email' => 'Username or Password is Invalid.']);
    }

    public function logout(){
        \Auth::logout();
        return view('Auth.login');
    }



}