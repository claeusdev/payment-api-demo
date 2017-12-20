<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Account;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $account_details = Account::where('user_id',Auth::user()->id)->get();
        // $user_id = $account_details->user_id;
        // echo $user_id;
        // $user = User::where('id', Auth::);
        // echo $account_details;
        if( is_null( Auth::user()->account ) )
        {
            Account::create(['user_id'=>Auth::user()->id,'balance'=>500,'currency'=>'GHC']);

        }

        return view('home');
    }
}
