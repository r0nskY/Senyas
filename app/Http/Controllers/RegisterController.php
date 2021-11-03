<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: ADSOPH CROWN
 * Date: 4/7/2017
 * Time: 4:56 PM
 */
use Illuminate\Http\Request;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;



class RegisterController extends Controller
{
    use AuthenticatesAndRegistersUsers;

    public function registerform(Request $request) {

        if ($request->get('submit')) {
            $rules = array(

                'email' => 'required|email|max:255|unique:users',
                'password' => 'required',

            );
            $validator = \Validator::make(\Input::all(), $rules);
            if ($validator->fails()) {
                return \Redirect::to('/register')
                    ->withErrors($validator) // send back all errors to the login form
                    ->withInput(\Input::except('password')); // send back the input (not the password) so that we can repopulate the form
            }else {
                $user = new User();
                $user->user_type = 0;
                $user->fname = $request->get('fname');
                $user->mname = $request->get('mname');
                $user->lname = $request->get('lname');
                $user->street = $request->get('street');
                $user->barangay = $request->get('barangay');
                $user->municipality = $request->get('municipality');
                $user->province = $request->get('province');
                $user->position = $request->get('position');
                $user->ass_mun = $request->get('ass_mun');
                $user->username = $request->get('username');
                $user->password = bcrypt($request->get('password'));
                $user->email = $request->get('email');
                $user->birthdate = $request->get('birthdate');
                $user->save();
            }
        }





        return $this->view();


    }





}
