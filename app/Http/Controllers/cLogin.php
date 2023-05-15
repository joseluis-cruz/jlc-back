<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ApiSession;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class cLogin extends Controller
{

    public function login_google_token(Request $request)
    {
        $token = $request->token;        
        $resp = (object) [
            'action' => "login_google_token",
            'status' => "error"            
        ];
        $data = (object) [];

        if (isset($token))
        {
            try
            {
                $user = Socialite::driver('google')->userFromToken($token); 
            }
            catch (Exception $e)
            {
                if (isset($user)) { unset($user); }
                $resp->message = "Google token validation error: " . $e->getMessage();
            }
        }
        else
        {
            $resp->message = "Google token missing";
        }
        
        if (isset($user))
        {
            $local_user =  User::where('email', $user->email)->first();
            if (isset($local_user))
            {
                $cambios =
                    ($local_user->email != $user->email) ||
                    ($local_user->google_id != $user->id) ||
                    ($local_user->avatar != $user->avatar) ||
                    ($local_user->avatar_original != $user->avatar_original) ||
                    (!$local_user->email_verified_at);
                if (!$local_user->email_verified_at)
                {
                    $local_user->email_verified_at = now();
                }
                if ($cambios)
                {
                    $local_user->email = $user->email;
                    $local_user->google_id = $user->id;
                    $local_user->avatar = $user->avatar;
                    $local_user->avatar_original = $user->avatar_original;
                    $local_user->save();
                }
            }
            else
            {
                $local_user = new User();
                $local_user->name = $user->name;
                $local_user->email = $user->email;
                $local_user->email_verified_at = now();
                $local_user->password = "?";
                $local_user->api_token = Str::random(60);
                $local_user->google_id = $user->id;
                $local_user->avatar = $user->avatar;
                $local_user->avatar_original = $user->avatar_original;
                $local_user->save();
            }
            $data->user = $local_user;
            $data->picture = $local_user->avatar;
            $apis = new ApiSession();
            $apis->init($local_user->id);
            $apis->save();
            $apis->setValue('user_name',$local_user->name);            
            $apis->setValue('user_email',$local_user->email);
            $data->api_key = $apis->api_key;
            $resp->status = "ok";
            $resp->data = $data;
        }
        return response()->json($resp);        
    }

    public function logout(Request $request)
    {
        $resp = $this->session_status($request,"logout");
        if ($resp->status=="ok")
        {
            $resp->session->close();
            $resp->session->save();
        }
        unset($resp->session);
        return response()->json($resp);
    }

    // ---- métodos para uso interno:
    
    public function session_status(Request $request, string $action)
    {
        $resp = (object) [
            'action' => $action,
        ];
        try
        {
            $ses = ApiSession::where('api_key',$request->api_key)->first();
        }
        catch (Exception $e) {
            $resp->status = "error";
            $resp->message = $e->getMessage();
        }
        if (isset($ses))
        {
            if ($ses->status==="A")
            {
                $resp->status = "ok";
                $resp->session = $ses;
            }
            else {
                $resp->status = "error";
                $resp->message = "session not active";
            }
        }
        else {
            $resp->status = "error";
            $resp->message = "session not found";
        }
        return $resp;
    }

}
