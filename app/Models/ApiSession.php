<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiSession extends Model
{
    use HasFactory;

    public const API_KEY_LENGTH = 200;

    protected $table = "api_session";

    public function init ($user_id)
    {
        $this->api_key = Str::random(ApiSession::API_KEY_LENGTH);
        $this->user_id = $user_id;
        $this->status = "A";
    }

    public function close ()
    {
        $this->status = "C";        
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function envs()
    {
        return $this->hasMany(ApiSessionEnv::class);
    }

    public function setValue($var_name,$var_value)
    {
        $env = ApiSessionEnv::where('api_session_id',$this->id)->where('var_name',$var_name)->first();
        if (!isset($env)) {
            $env = new ApiSessionEnv();
            $env->api_session_id = $this->id;
            $env->var_name = $var_name;
        }
        $env->setValue($var_value);
        $env->save();
    }

    public function getValue($var_name,$default_value)
    {
        $env = ApiSessionEnv::where('api_session_id',$this->id)->where('var_name',$var_name)->first();
        if (isset($env)) {
            return $env->getValue();
        }
        else {
            return $default_value;
        }
    }
}
