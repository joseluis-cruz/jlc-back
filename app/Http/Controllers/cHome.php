<?php

namespace App\Http\Controllers;
//use Illuminate\Http\Request;

class cHome extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @ return void
     */
    /*
    public function __construct()
    {
        $this->middleware('auth');
    }
    */
    

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('authHome');
    }
}
