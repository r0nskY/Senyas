<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: ADSOPH CROWN
 * Date: 4/7/2017
 * Time: 4:56 PM
 */
use Illuminate\Http\Request;
use App\User;
use Validator;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Department;
use App\Responder_response;
use App\Alerts;
use App\Barangay;
use App\App_user;
use App\Alerts_ac;
use App\ActionCenter;


class ActionCenterController extends Controller
{

    public function homeaction($assmun, Request $request) {
        $this->data['acname'] = $assmun;
       /* echo "POGI";*/
      if($request->get('submit')) {

          /*echo $assmun;*/
          $id = \Auth::user('id')->where('municipality',$assmun);
          $current_password = \Auth::User()->password;
          /*echo Hash::check($request->get('old_pass'),$current_password);*/
          if(Hash::check($request->get('old_pass'),$current_password)){
               // echo $current_password;

              if(($request->get('Confirm_Pass')==($request->get('New_Password')))){
                  \Auth::user()
                  ->where('username', $assmun)
                  ->update(['password' => Hash::make($request->get('Confirm_Pass'))
                  ]);

                  /*alert("SUCCESS");*/

                  echo "SUCCESS";
                  return redirect("/logout");

              }
              else{

                    echo "New Password did not match";
                  //Alert::message('New Password did not match!');
              }


              /*\Auth::User('password')->where('municipality',$assmun)->update(['password',Hash::make($request->get('Confirm_Pass')),]);*/

          }
          else{
              /*alert( "Invalid Old Password");*/

              echo "INVALID OLD PASSWORD";
              //Alert::message('Invalid Old Password');
          }
      }

        $userid = ActionCenter::select('id')->where('name',$assmun)->get();

        foreach($userid as $t){
            $idd =$t->id;
        }
/*
        $brgy = Barangay::join('responder','responder.st_id',"=", 'barangay.id')->where('responder.dep_id',4)->where('responder.ac_id',$idd)->groupby('barangay.id')->orderby('barangay', 'asc')->get();
        $this->data['brgy'] = $brgy;*/

        $med = Barangay::join('responder','responder.st_id',"=", 'barangay.id')->where('responder.dep_id',1)->where('responder.ac_id',$idd)->groupby('barangay.barangay')->orderby('barangay', 'asc')->get();
        $this->data['med'] = $med;

        $fire = Barangay::join('responder','responder.st_id',"=", 'barangay.id')->where('responder.dep_id',2)->where('responder.ac_id',$idd)->groupby('barangay.barangay')->orderby('barangay', 'asc')->get();
        $this->data['fire'] = $fire;

        $brgy1 = Barangay::where('ac_id', $idd)->where('dept_id', 4)->orderby('barangay', 'asc')->get();
        $this->data['brgy1'] = $brgy1;

        $med1 = Barangay::where('ac_id', $idd)->where('dept_id', 1)->orderby('barangay', 'asc')->get();
        $this->data['med1'] = $med1;


        $fire1 = Barangay::where('ac_id', $idd)->where('dept_id', 2)->orderby('barangay', 'asc')->get();
        $this->data['fire1'] = $fire1;

        $pnp1 = Barangay::where('ac_id', $idd)->where('dept_id', 3)->orderby('barangay', 'asc')->get();
        $this->data['pnp1'] = $pnp1;




        return $this->view();
    }

    public function Reports($assmun) {

        $this->data['acmun'] = ucfirst($assmun);

       /* $actioncenter = ActionCenter::select('id')->where('name',$assmun)->get();
        foreach($actioncenter as $t){
            $id =$t->id;
        }

      //  echo ($actioncenter);
        $alerts_ac = Alerts_ac::join('alerts', 'alerts.id', "=", 'alert_ac.alert_id')->join( 'action_center', 'action_center.id', "=", "alert_ac.ac_id")->join('app_users','app_users.imei',"=",'alerts.imei')->where('alert_ac.status',2)->where('alert_ac.ac_id',$id)->get();
        $this->data['alerts_ac'] = $alerts_ac;
        $alerts = Alerts::join('app_users','app_users.imei',"=",'alerts.imei')->join('alert_ac', 'alert_ac.alert_id', "=", 'alerts.id')->join( 'action_center', 'action_center.id', "=", "alert_ac.ac_id")->where('alerts.status',2)->where('alert_ac.ac_id',$id)->get();
        $this->data['alerts'] = $alerts;
        $usercneas = User::where('user_type',0)->get();
        $this->data['usercneas'] = $usercneas;*/
        $alerts_ac = Alerts_ac::join('alerts', 'alerts.id', "=", 'alert_ac.alert_id')->join( 'action_center', 'action_center.id', "=", "alert_ac.ac_id")->join('app_users','app_users.imei',"=",'alerts.imei')->where('alert_ac.status',2)->get();
        $this->data['alerts_ac'] = $alerts_ac;
        $alerts = Alerts::join('app_users','app_users.imei',"=",'alerts.imei')->where('alerts.status',2)->get();
        $this->data['alerts'] = $alerts;


        $usercneas = User::where('user_type',0)->get();
        $this->data['usercneas'] = $usercneas;

            // $this->data['Reports'] = 'Reports';
        return $this->view();
    }

    public function Responders($assmun) {

        $this->data['acmun'] = ucfirst($assmun);

        $actioncenter = ActionCenter::select('id')->where('name',$assmun)->get();
        foreach($actioncenter as $t){
            $id =$t->id;
        }

        //  echo ($actioncenter);
        //  echo ($actioncenter);
        $responders = Responder_response::join('barangay', 'barangay.id', '=', 'responder.st_id')->where('responder.ac_id',$id)->get();
        $this->data['responders']=$responders;

        $usercneas = User::where('user_type',0)->get();
        $this->data['usercneas'] = $usercneas;

        // $this->data['Reports'] = 'Reports';


        return $this->view();
    }

    public function Responders1($assmun, $department, Request $request) {

        $this->data['acmun'] = ucfirst($assmun);
        $this->data['acmun1'] = ($assmun);
        $this->data['dep'] = ucfirst($department);


        if($request->get('submit')) {

            /*echo $assmun;*/
            Responder_response::where('st_id', $request->get('submit'))->update(['fname' => $request->get('fname')]);
            Barangay::where('id', $request->get('submit'))->update(['barangay' => $request->get('fname')]);


            $actioncenter = ActionCenter::select('id')->where('name',$assmun)->get();
            foreach($actioncenter as $t){
                $id =$t->id;
            }
            $depart = Department::select('id')->where('department',$department)->get();
            foreach($depart as $ti){
                $did =$ti->id;
            }
            //  echo ($actioncenter);
            $responders = Responder_response::where('ac_id',$id)->where('dep_id',$did)->get();
            $this->data['responders']=$responders;



            $usercneas = User::where('user_type',0)->get();
            $this->data['usercneas'] = $usercneas;
            return $this->view();

        }else{

            $actioncenter = ActionCenter::select('id')->where('name',$assmun)->get();
            foreach($actioncenter as $t){
                $id =$t->id;
            }
            $depart = Department::select('id')->where('department',$department)->get();
            foreach($depart as $ti){
                $did =$ti->id;
            }
            //  echo ($actioncenter);
            $responders = Responder_response::where('ac_id',$id)->where('dep_id',$did)->get();
            $this->data['responders']=$responders;



            $usercneas = User::where('user_type',0)->get();
            $this->data['usercneas'] = $usercneas;
            return $this->view();

        }
        
        
        
        




        return $this->view();
    }
    public function mgausers(Request $request) {

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

        return $this->view();
    }

    public function department($assmun, $department, Request $request) {
        $this->data['acname'] = $assmun;
        $this->data['dep'] = $department;
       // $this->data['acmun'] = ucfirst($assmun);




        if($request->get('submit')) {

            /*echo $assmun;*/
            $id = \Auth::user('id')->where('municipality',$assmun);
            $current_password = \Auth::User()->password;
            /*echo Hash::check($request->get('old_pass'),$current_password);*/
            if(Hash::check($request->get('old_pass'),$current_password)){
                // echo $current_password;

                if(($request->get('Confirm_Pass')==($request->get('New_Password')))){
                    \Auth::user()
                        ->where('municipality', $assmun)
                        ->where('position', $department)
                        ->update(['password' => Hash::make($request->get('Confirm_Pass'))
                        ]);

                    /*alert("SUCCESS");*/

                    echo "SUCCESS";
                    return redirect("/logout");

                }
                else{

                    echo "New Password did not match";
                    //Alert::message('New Password did not match!');
                }


                /*\Auth::User('password')->where('municipality',$assmun)->update(['password',Hash::make($request->get('Confirm_Pass')),]);*/

            }
            else{
                /*alert( "Invalid Old Password");*/

                echo "INVALID OLD PASSWORD";
                //Alert::message('Invalid Old Password');
            }
        }








        $userid = ActionCenter::select('id')->where('name',$assmun)->get();
        $depid = Department::select('id')->where('department', $department)->get();
        foreach($userid as $t){
            $idd2 =$t->id;
        }
        foreach ($depid as $did){
            $dpid = $did->id;
        }

        $resp = Barangay::join('responder','responder.st_id',"=", 'barangay.id')->where('responder.dep_id',$dpid)->where('responder.ac_id',$idd2)->groupby('barangay.id')->orderby('barangay', 'asc')->get();

        $this->data['resp'] = $resp;

        $resp1 = Barangay::where('dept_id',$dpid)->where('ac_id',$idd2)->orderby('barangay', 'asc')->get();
        $this->data['resp1'] = $resp1;
        // $this->data['Reports'] = 'Reports';
        return $this->view();
    }




    public function infoblast($assmun) {

       /* $this->data['acmun'] = ucfirst($assmun);

        $actioncenter = ActionCenter::select('id')->where('name',$assmun)->get();
        foreach($actioncenter as $t){
            $id =$t->id;
        }
        //  echo ($actioncenter);
        $responders = Responder_response::where('ac_id',$id)->where('dep_id',4)->get();
        $this->data['responders']=$responders;



        $usercneas = User::where('user_type',0)->get();
        $this->data['usercneas'] = $usercneas;*/



        $this->data['acmun'] = $assmun;

        // $this->data['Reports'] = 'Reports';


        return $this->view();
    }





    public function upload($assmun){
        $this->data['acmun']=$assmun;
        if($_FILES['file']['size']>0){
            if($_FILES['file']['size']<=5000000){
                if(move_uploaded_file($_FILES['file']['tmp_name'],public_path('img')."/".$_FILES['file']['name'])){
                    $publicpath = public_path('img')."/".$_FILES['file']['name'];
                    $this->data['publicpath']= $publicpath;

                    ?>
                    <script type="text/javascript">

                        parent.document.getElementById("message").innerHTML="";
                        parent.document.getElementById("file").value="";
                        window.parent.updatepicture("<?php echo asset("/img/".$_FILES['file']['name']);?>");

                    </script>


                    <?php

                }

            }
        }

    }




}
