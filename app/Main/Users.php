<?php

namespace App\Main;

use App\Models\MdDepartment;
use App\Models\MdVisitor;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Users {
    /**
     * Get current user info.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function currentUser(){
        if (Auth::user()) {
            $authUser = Auth::user();
        
            $user = User::with('department')->find($authUser->id);

            return $user;
        }
        return null;
    }

    /**
     * Get user info by id
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function userInfo($userId) {
        $user = User::with('department')->find($userId);
        return $user;
    }

    /**
     * Get user info by id
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function userInfoByUsername($username) {
        $user = User::with('department')->where('username', $username)->first();
        return $user;
    }

    /**
     * get all department and user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function getAllUser() {
        return User::with('department')->get();
    }

    /**
     * get moderator user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function getModerators()
    {
        return User::where('role', Utils::PHO_QUAN_LY)->get();
    }

    /**
     * get all department and user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function getAllDepartment() {
        return MdDepartment::get();
    }

    /**
     * get all department and user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function getAllDepartmentUser() {
        return MdDepartment::with('users')->get();
    }

    /**
     * get all user of department by department id
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function getDepartmentUser($departmentId) {
        return MdDepartment::with('users')->find($departmentId);
    }
    /**
     * get all user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function userList($request)
    {
        $users = User::with('department');

        if ( $request->has('name')) {
            $users->where('name', 'like', '%'.$request->input('name').'%');
        }

        if ($request->has('dept') && $request->input('dept') != "") {
            $users->where('department_id', $request->input('dept'));
        }
        $users->orderBy('name');

        if ( $request->has('name')) {
            $sReturn = $users->paginate(20)->appends(['name' => $request->input('name'), 'dept' => $request->input('dept')]);
        } else {
            $sReturn = $users->paginate(20);
        }
        
        return $sReturn;
    }

    public static function TrackUser($request)
    {
        try{
            if ( $request->method() == 'POST' ){
                $visitor = new MdVisitor();
                $visitor->ip = $request->ip();
                $visitor->route = $request->url();
                $visitor->method = $request->method();
                $visitor->save();
            }
        }catch(Exception $e){}
    }
}