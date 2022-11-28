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
        return json_encode($resp);        
    }
    
    public function logout(Request $request)
    {
        $resp = (object) [
            'action' => 'logout',
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
            if ($ses->status==="A") {
                $ses->close();
                $ses->save();
                $resp->status = "ok";
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
        return json_encode($resp);
        /*
        if (Auth::user())
        {
            $loggedUser = User::where('email', Auth::user()->email)->first();
        }
        else
        {
            unset($loggedUser);
        }
        auth()->logout();
        if (isset($loggedUser))
        {
            $loggedUser->api_token = null;
            $loggedUser->save();
        }
        */
    }

    /*
    public function login_csrf_token(Request $request)
    {
        $token = csrf_token();
        $resp = (object) [
            'action' => "login_csrf_token",
            'status' => "ok",
            'data' =>[
                'token' => $token
            ]
        ];
        return json_encode($resp);
    }
    
    public function login_google_status()
    {
        try
        {
            $user = Auth::user();
        }
        catch (\Exception $e)
        {
            if (isset($user)) { unset($user); }
        }
        $resp = (object) [];
        $resp->action = 'login_google_status';
        if (isset($user))
        {
            $resp->result = 'ok';
            $resp->data = $user;
        }
        else
        {
            $resp->result = 'error';
            $resp->message = 'not authenticated';
            $resp->data = (object) ['redirect' => route('login_google_redirect')];
        }
        return json_encode($resp);
    }
    */
    /**
     * 
     * Redirect the user to the Google authentication page.
    *
    * @ return \Illuminate\Http\Response
    */
    /*
    public function login_google_redirect()
    {
        $resp = (object) [
            'action' => 'login_google_redirect',
            'result' => 'ok',
            'result' => ['data' => Socialite::driver('google')->redirect()]
        ];
        return json_encode($resp);
        return Socialite::driver('google')->redirect();
    }
    */

    /**
     * Obtain the user information from Google.
     *
     * @ return \Illuminate\Http\Response
     */
    /*
    public function login_google_callback()
    {
        try
        {
            $user = Socialite::driver('google')->user();
        }
        catch
        (\Exception $e)
        {
            return redirect('/login');
        }
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser)
        {
            $existingUser->api_token       = Str::random(60);
            $existingUser->save();
            auth()->login($existingUser, true); // log them in
        }
        else
        {
            // create a new user
            $newUser                  = new User;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $newUser->google_id       = $user->id;
            $newUser->avatar          = $user->avatar;
            $newUser->avatar_original = $user->avatar_original;
            $newUser->password = "?";
            $newUser->api_token       = Str::random(60);
            $newUser->save();
            auth()->login($newUser, true);
        }
        return redirect()->to('/');
    }
    */
}
