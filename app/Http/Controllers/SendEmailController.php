<?php

namespace App\Http\Controllers;

use App\Main\Utils;
use App\Models\MdRequest;
use App\Models\User;
use App\Notifications\AssignRequest;
use App\Notifications\AssignRequestInform;
use App\Notifications\completeRequest;
use App\Notifications\extendRequest;
use App\Notifications\extendRequestResult;
use App\Notifications\returnRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class SendEmailController extends Controller
{
    /**
     * send assign mail
     */
    public static function assignMail($mdRequest)
    {
        $handler = $mdRequest->handler;
        $sub_handler = $mdRequest->sub_handler;
        try{
            if ($handler) {
                $receipients = $handler->pluck('name', 'email');
                Notification::route('email', $receipients)->notify(new AssignRequest($mdRequest));
            }
            if ($sub_handler){
                $receipients = $sub_handler->pluck('name', 'email');
                Notification::route('email', $receipients)->notify(new AssignRequest($mdRequest));
            }
            $receipients = $mdRequest->requester->pluck('name', 'email');
            Notification::route('email', $receipients)->notify(new AssignRequestInform($mdRequest));
        } catch (Exception $e){}
    }

    /**
     * send complete mail
     */
    public static function completeMail($mdRequest){
        $mdCompleteRequest = MdRequest::with('department','requester','assign','handler','files','type','sub_handler.user')->where('request_id', $mdRequest->request_id)->first();
        try{
            $receipients = $mdCompleteRequest->requester->pluck('name', 'email');
            Notification::route('email', $receipients)->notify(new completeRequest($mdCompleteRequest, '/my-requests?tab=my-req-tab&id='.$mdCompleteRequest->request_id));
            if ( Auth::user()->role == Utils::PHO_QUAN_LY ) {
                $admin = User::where('role', Utils::QUAN_LY)->get();
                $receipients = $admin->pluck('name', 'email');
                Notification::route('email', $receipients)->notify(new completeRequest($mdCompleteRequest, '/dministrator?tab=completed-req-tab&id='.$mdCompleteRequest->request_id));
            }
        } catch (Exception $e) {}
    }

    /**
     * send complete mail
     */
    public static function returnMail($mdRequest){
        $mdReturnRequest = MdRequest::with('department','requester','assign','handler','files','type','sub_handler.user')->where('request_id', $mdRequest->request_id)->first();
        try{
            $admin = User::where('role', Utils::QUAN_LY)->get();
            $receipients = $admin->pluck('name', 'email');
            Notification::route('email', $receipients)->notify(new returnRequest($mdReturnRequest));
        } catch (Exception $e) {}
    }

    /**
     * send complete mail
     */
    public static function extendMail($mdRequest){
        $mdExtendRequest = MdRequest::with('department','requester','assign','handler','files','type','sub_handler.user')->where('request_id', $mdRequest->request_id)->first();
        try{
            $admin = User::where('role', Utils::QUAN_LY)->get();
            $receipients = $admin->pluck('name', 'email');
            Notification::route('email', $receipients)->notify(new extendRequest($mdExtendRequest));
        } catch (Exception $e) {}
    }

    /**
     * send complete mail
     */
    public static function extendResultMail($mdRequest){
        $mdExtendRequest = MdRequest::with('department','requester','assign','handler','files','type','sub_handler.user')->where('request_id', $mdRequest->request_id)->first();
        try{
            $handler = $mdExtendRequest->handler();
            $receipients = $handler->pluck('name', 'email');
            Notification::route('email', $receipients)->notify(new extendRequestResult($mdExtendRequest));
        } catch (Exception $e) {}
    }
}
