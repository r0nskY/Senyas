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
use App\Department;
use App\Responder_response;
use App\Alerts;
use App\Alerts_ac;
use App\ActionCenter;
use App\App_user;


class CommandCenterController extends Controller
{

    public function home() {

        $this->data['page'] = "home";
        return $this->view();


    }

    public function reports() {



        //  echo ($actioncenter);
        $alerts_ac = Alerts_ac::join('alerts', 'alerts.id', "=", 'alert_ac.alert_id')->join( 'action_center', 'action_center.id', "=", "alert_ac.ac_id")->join('app_users','app_users.imei',"=",'alerts.imei')->where('alert_ac.status',2)->get();
        $this->data['alerts_ac'] = $alerts_ac;
        $alerts = Alerts::join('app_users','app_users.imei',"=",'alerts.imei')->where('alerts.status',2)->get();
        $this->data['alerts'] = $alerts;


        $usercneas = User::where('user_type',0)->get();
        $this->data['usercneas'] = $usercneas;





        // $this->data['Reports'] = 'Reports';


        return $this->view();
    }

    public function appUsers(Request $request) {







        if($request->get('submit')){

            App_user::where('imei', $request->get('submit'))->update(['status' => 1]);

            $app_user1 = App_user::where('status',1)->get();
            $this->data['app_user1']=$app_user1;

            $app_user = App_user::where('status',0)->get();
            $this->data['app_user']=$app_user;


            return $this->view();
        }elseif($request->get('submit1')){

            App_user::where('imei', $request->get('submit1'))->update(['status' => 0]);

            $app_user1 = App_user::where('status',1)->get();
            $this->data['app_user1']=$app_user1;

            $app_user = App_user::where('status',0)->get();
            $this->data['app_user']=$app_user;


            return $this->view();
        }
        else{
            $app_user1 = App_user::where('status',1)->get();
            $this->data['app_user1']=$app_user1;

            $app_user = App_user::where('status',0)->get();
            $this->data['app_user']=$app_user;
        }


        echo $request->get('submit');
        // $this->data['Reports'] = 'Reports';
        return $this->view();


    }





}
