<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: ADSOPH CROWN
 * Date: 4/11/2017
 * Time: 3:29 PM
 */
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\File;

use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Department;
use App\Responder_response;
use App\Alerts;
use App\Barangay;
use App\App_user;
use App\Alerts_ac;
use App\ActionCenter;

class TrackLocationController extends Controller
{

    public function trackLocation($assmun, $imei)
    {
        $this->data['acname'] = $assmun;
        $this->data['imei'] = $imei;

        $userid = ActionCenter::select('id')->where('name',$assmun)->get();


        foreach($userid as $t){

            $idd =$t->id;

        }


        $brgy = Barangay::join('responder','responder.st_id',"=", 'barangay.id')->where('responder.dep_id',4)->where('responder.ac_id',$idd)->groupby('barangay.barangay')->orderby('barangay', 'asc')->get();
        $this->data['brgy'] = $brgy;


        $med = Barangay::join('responder','responder.st_id',"=", 'barangay.id')->where('responder.dep_id',1)->where('responder.ac_id',$idd)->groupby('barangay.barangay')->orderby('barangay', 'asc')->get();
        $this->data['med'] = $med;

        $appuser = App_user::join('alerts','alerts.imei',"=",'app_users.imei')->where('app_users.imei',$imei)->where('alerts.status',0 or 1)->get();
        $this->data['alertinfo']=$appuser;
        return $this->view();

    }


}