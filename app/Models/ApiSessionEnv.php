<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiSessionEnv extends Model
{
    use HasFactory;

    protected $table = "api_session_env";

    public function setValue($value)
    {
        $this->var_value_str = null;
        $this->var_value_int = null;
        $this->var_value_dob = null;
        $this->var_value_dtm = null;
        $this->var_value_boo = null;
        $varType = gettype($value);
        if ($varType==="string") { $this->var_value_str = $value; }
        else if ($varType==="integer") { $this->var_value_int = $value; }
        else if ($varType==="double") { $this->var_value_dob = $value; }
        else if ($value instanceof DateTime) { $this->var_value_dtm = $value; }
        else if ($varType==="boolean") { $this->var_value_boo = $value; }
        else if ($varType!="") { $this->var_value_str = "(" . $varType . ") " . $value; }
    }

    public function getValue()
    {
        if ($this->var_value_str) { return ($this->var_value_str); }
        else if ($this->var_value_int) { return ($this->var_value_str); }
        else if ($this->var_value_dob) { return ($this->var_value_dob); }
        else if ($this->var_value_dtm) { return ($this->var_value_dtm); }
        else if ($this->var_value_boo) { return ($this->var_value_boo); }
        else return null;
    }
}
