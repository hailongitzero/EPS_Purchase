<?php

namespace App\Http\Controllers;

use Adldap;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Config;

class LoginController extends Controller
{

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string|regex:/^[A-Za-z]+\.[A-Za-z]+$/',
            'password' => 'required|string',
        ]);
    }

    public function attemptLogin(Request $request)
    {
        
      $config = [
        // Mandatory Configuration Options
        'hosts'            => ['10.144.23.1'],
        'base_dn'          => '',
        'username'         => 'thientt',
        'password'         => 'Ep$@20.23$?',
    
        // Optional Configuration Options
        'schema'           => Adldap\Schemas\ActiveDirectory::class,
        'port'             => 389,
        'follow_referrals' => false,
        'use_ssl'          => false,
        'use_tls'          => false,
        'version'          => 3,
        'timeout'          => 5,
    
        // Custom LDAP Options
        'custom_options'   => [
            // See: http://php.net/ldap_set_option
            LDAP_OPT_X_TLS_REQUIRE_CERT => LDAP_OPT_X_TLS_HARD
        ]
    ];
        $ad = new Adldap\Adldap();
        $ad->addProvider($config, 'eps');
        $username = 'thientt';
        $password = 'Ep$@20.23$?';
        $provider = $ad->connect("eps", $username, $password, false);

        dd($provider);
    }
}
