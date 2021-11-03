<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: ADSOPH CROWN
 * Date: 4/7/2017
 * Time: 4:56 PM
 */
use Illuminate\Http\Request;

use Validator;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Department;
use App\Responder_response;
use App\Alerts;
use App\Alerts_ac;
use App\ActionCenter;
use App\App_user;
use App\User;
class ChangepassController extends Controller
{
    use AuthenticatesAndRegistersUsers;
    public function changepass(Request $request) {

       /* echo 'pogi';
      echo $request->get('submit');
        echo 'panget';
        echo $request->get('username');*/
        $this->data['username']=$request->get('username');
        /*echo $request->get('submit');*/

        if($request->get('submit')) {
           /* echo $request->get('username');*/

            User::where('username', $request->get('username'))->update(['password' => Hash::make($request->get('password'))]);

            /*\Auth::User('password')->where('municipality',$assmun)->update(['password',Hash::make($request->get('Confirm_Pass')),]);*/
            /*echo hash::make($request->get('Confirm_Pass'));*/
            echo "SUCCESS";

        }




        return $this->view();



    }


















}
