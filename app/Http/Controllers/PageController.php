<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Main\Dashboard;
use App\Main\Request;
use App\Main\RequestType;
use App\Main\ResourceType;
use App\Main\Users;
use App\Main\Utils;
use App\Models\MdRequest;
use App\Models\MdRequestType;
use App\Models\MdVisitor;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    /**
     * dashboard index
     */
    public function dashboard(HttpRequest $request)
    {
        return view('pages/request/dashboard', [
            'totalDailyRequest' => Dashboard::totalDailyRequest(),
            'totalMyRequest' => Dashboard::totalMyRequest(),
            'requestType' => RequestType::getType(),
            'lastActive' => Dashboard::getLatestActiveRequest(),
            'totalVisit' => MdVisitor::count(),
            'totalDailyVisit' => MdVisitor::whereDate('date', date('Y-m-d'))->count(),
        ]);
    }

    /**
     * Show specified view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function makeRequest(HttpRequest $request)
    {
        return view('pages/request/makeRequest', [
            'allUser' => Users::getAllUser(),
            'deptUser' => Users::getAllDepartmentUser(),
            'reqTp' => Request::getRequestType(),
            'resource' => ResourceType::getType(),
        ]);
    }

    /**
     * Get new requests
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adminRequestManager(HttpRequest $request)
    {
        return view('pages/request/adminRequestManager', [
            'moderator' => Users::getModerators(),
            'requestTp' => MdRequestType::get(),
            'resource' => ResourceType::getType(),
            'deptUser' => Users::getAllDepartmentUser(),
            'totalRequest' => MdRequest::count(),
            'totalNewRequest' => MdRequest::where('status', Utils::YEU_CAU_MOI)->count(),
            'totalHandleRequest' => MdRequest::whereIn('status', [Utils::DANG_XU_LY, Utils::TIEP_NHAN])->count(),
            'totalReturnRequest' => MdRequest::whereIn('status', [Utils::CHUYEN_XU_LY, Utils::GIA_HAN])->count(),
            'totalMyRequest' => MdRequest::where('requester_id', Auth::user()->username)->count(),
        ]);
    }

    /**
     * Get new requests
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function modRequestManager(HttpRequest $request)
    {
        $username = Auth::user()->username;
        return view('pages/request/moderatorRequestManager', [
            'requestTp' => MdRequestType::get(),
            'resource' => ResourceType::getType(),
            'deptUser' => Users::getAllDepartmentUser(),
            'totalRequest' => MdRequest::count(),
            'totalHandleRequest' => MdRequest::whereIn('status', [Utils::DANG_XU_LY, Utils::TIEP_NHAN])
                ->where('handler_id', $username)
                ->orWhereHas('sub_handler.user', function($query) use($username) {
                return $query->where('username', $username);
                })->count(),
            'totalReturnRequest' => MdRequest::whereIn('status', [Utils::CHUYEN_XU_LY, Utils::GIA_HAN])
                ->where('handler_id', $username)
                ->orWhereHas('sub_handler.user', function($query) use($username) {
                return $query->where('username', $username);
                })->count(),
            'totalMyRequest' => MdRequest::where('requester_id', Auth::user()->username)->count(),
        ]);
    }

    /**
     * Show specified view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userManagement(HttpRequest $request)
    {
        // dd(strval($request->input('name')));
        return view('pages/users/userManagement', [
            'users' => Users::userList($request),
            'department' => Users::getAllDepartment(),
            'name' => $request->input('name'),
            'dept' => $request->input('dept'),
        ]);
    }

    /**
     * Show specified view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profile(HttpRequest $request, $username)
    {
        return view('pages/users/profile', [
            'user' => Users::userInfoByUsername($username),
            'role' =>Auth::user()->role,
            'department' => Users::getAllDepartment(),
        ]);
    }

    public function fileManage(HttpRequest $request){
        return view('pages/fileManage');
    }

    public function myRequests(HttpRequest $request)
    {
        return view('pages/request/userRequestManager');
    }
}
