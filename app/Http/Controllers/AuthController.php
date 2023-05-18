<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Main\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Show specified view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginView()
    {
        return view('login/main', [
            'layout' => 'login'
        ]);
    }

    /**
     * Authenticate login user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(DB::connection()->getDatabaseName()){
            var_dump( "Yes! Successfully connected to the DB: " . DB::connection()->getDatabaseName());
        }else{
            var_dump("Could not find the database. Please check your configuration.");
        }
        if (Auth::attempt([
            'email' => $request->input('email'), 
            'password' => $request->input('password'),
            'active' => 1,
        ])) {
            Users::TrackUser($request);
        } else {
            throw new \Exception('Wrong email or password.');
        }
    }

    /**
     * Logout user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }
}
