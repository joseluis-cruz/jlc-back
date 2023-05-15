<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\cLogin;
use App\Models\User;
use App\Models\App;
use Illuminate\Support\Facades\Http;

class cApp extends Controller
{
    //
    public function app_list(Request $request)
    {
        $resp = (new cLogin)->session_status($request,"app_list");
        if ($resp->status == "ok") {
            $apps = $resp->session->user->apps;
            $result = [];
            foreach ($apps as $app)
            {
                $result[] = App::where('id',$app->app_id)->select('code','name','description','url_main','url_get_auth','url_redirect')->first();
            } 
            $resp->result = $result;
        }
        unset($resp->session);
        return response()->json($resp);
    }

    public function app_get_auth(Request $request)
    {
        $resp = (new cLogin)->session_status($request,"app_get_auth");
        $app_code = $request->app_code;
        if ($resp->status == "ok") {
            $app = App::where("code", $app_code)->first();
            $url = $app->url_get_auth;
            $auth_response = Http::asForm()->post($url, [
                'eml' => $resp->session->user->email,
                'tkn' => md5($app->token.$resp->session->user->email)
            ]);
            if ($auth_response->status() == 200)
                $resp->result = json_decode($auth_response->body());//$auth_response->object();
            else
            {
                $resp->status = "error";
            }
        }
        unset($resp->session);        
        return response()->json($resp);        
    }
}