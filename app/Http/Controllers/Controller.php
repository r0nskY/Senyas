<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public $id = null;
    public $data = array();
    public $viewDir = "";

    public function __construct()
    {
        $this->data['page'] = '';
    }
   

    public function view($vid = null) {
        $caller = caller();
        if ($this->id == null) {
            $this->id = str_replace('\\', '/', str_replace('Controller', '', str_replace('App\\Http\\Controllers\\', '', $caller['class'])));
        }
        if ($vid == null) {
            $vid = $caller['function'];
        }
        if (!file_exists(base_path() . "/resources/views/" . $this->viewDir . "/" . $this->id . "/" . $vid . ".blade.php")) {
            if (substr($vid, 0, 1) != '@') {
                $paths = explode("/", $this->id);
                for ($i = 1; $i <= count($paths); $i++) {
                    $path = implode("/", array_slice($paths, 0, $i));
                    @mkdir(base_path() . "/resources/views/" . $this->viewDir . "/" . $path . "/");
                }

                file_put_contents(base_path() . "/resources/views/" . $this->viewDir . "/" . $this->id . "/" . $vid . ".blade.php", "");
            }
        }

        if (substr($vid, 0, 1) == '@') {
            $vid = substr($vid, 1);
        } else {
            $vid = $this->id . "." . $vid;
        }

        $vid = str_replace("\\", ".", $vid);

        $v = $this->viewDir . "." . $vid;
        if($v[0] == ".") $v = substr($v, 1);
        return \View::make($v, $this->data);
    }
}

if (!function_exists('caller')) {
    function caller($function = NULL, $use_stack = NULL) {
        $e = new \Exception();
        $trace = $e->getTrace();
        $last_call = $trace[2];
        return ($last_call);
    }
}
if (!function_exists('d')) {
    function d() {
        echo "<pre>";
        foreach (func_get_args() as $v) {
            dump($v);
        }
        echo "</pre>";
    }
}

if (!function_exists('getallheaders')) {
    function getallheaders() {
        $headers = '';
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', (strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

if (!function_exists('base64_url_encode')) {
    function base64_url_encode($input) {
        return strtr(base64_encode($input), '+/=', '-_,');
    }
}

if (!function_exists('base64_url_decode')) {
    function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }


}