<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddNewReqRequest;
use App\Main\Request as MainRequest;
use App\Main\Utils;
use App\Models\MdRequest;
use App\Models\User;
use App\Notifications\NewRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class RequestController extends Controller
{

    /**
     * Nhập yêu cầu mới
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Responsea
     */
    public function get(Request $request) {

    }

    /**
     * Nhập yêu cầu mới
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Responsea
     */
    public function add(AddNewReqRequest $request){
        $validated = $request->validated();
        if ($validated) {
            DB::beginTransaction();
            $newRequest = CreateRequestController::create($request);
            
            if ($newRequest) {
                try{
                    // attach request file
                    FileUploadController::attachFile($request, $newRequest->request_id);
                } catch (Exception $e) {
                    DB::rollBack();
                    return response(['info' => 'failure', 'Content' => 'Gửi yêu cầu không thành công.'], 500)->header('Content-Type', 'application/json');
                }
                // Send Email
                // try{
                    $mailData = MdRequest::with('department','requester','handler','files','type','sub_handler')->find($newRequest->request_id);
                    $manager = User::where('role', Utils::QUAN_LY)->get();
                    Notification::send($manager, new NewRequest($mailData));
                // } catch (Exception $e) {}
                
            } else {
                DB::rollBack();
                return response(['info' => 'failure', 'Content' => 'Gửi yêu cầu không thành công.'], 500)->header('Content-Type', 'application/json');
            }
        }
        DB::commit();
        return response(['info' => 'success', 'Content' => 'Gửi yêu cầu thành công.'], 200)->header('Content-Type', 'application/json');
    }

    /**
     * Get all requests
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Responsea
     */
    public function allRequests(Request $request) {
        $params = $request->all();
        $params['status'] = null;
        $requests = MainRequest::fetchRequest($params);

        return response($requests, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Get news requests
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Responsea
     */
    public function newRequest(Request $request) {
        $params = $request->all();
        $params['status'] = [Utils::YEU_CAU_MOI];
        $requests = MainRequest::fetchRequest($params);

        return response($requests, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Get handling requests
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Responsea
     */
    public function handlingRequest(Request $request) {
        $params = $request->all();
        $params['status'] = [Utils::TIEP_NHAN, Utils::DANG_XU_LY];
        $requests = MainRequest::fetchRequest($params);

        return response($requests, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Get handling requests
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Responsea
     */
    public function handleRequest(Request $request) {
        $params = $request->all();
        $user = Auth::user();
        $params['handler'] = $user->username;
        $role = $user->role;
        $params['role'] = $role;
        $params['status'] = [Utils::TIEP_NHAN, Utils::DANG_XU_LY];
        $requests = MainRequest::fetchRequest($params);

        return response($requests, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Get completed requests
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Responsea
     */
    public function completedRequest(Request $request) {
        $params = $request->all();
        $params['status'] = [Utils::HOAN_THANH, Utils::TU_CHOI];
        if (Auth::user()->role == Utils::PHO_QUAN_LY){
            $params['handler'] = Auth::user()->username;
        }
        $requests = MainRequest::fetchRequest($params);

        return response($requests, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Get my requests
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Responsea
     */
    public function extendReturnRequests(Request $request) {
        $params = $request->all();
        $params['status'] = [Utils::GIA_HAN, Utils::CHUYEN_XU_LY];
        if (Auth::user()->role == Utils::PHO_QUAN_LY){
            $params['role'] = Auth::user()->role;
            $params['handler'] = Auth::user()->username;
        }
        $requests = MainRequest::fetchRequest($params);

        return response($requests, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Get my requests
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Responsea
     */
    public function myRequests(Request $request) {
        $params = $request->all();
        $params['status'] = null;
        $params['myRequest'] = true;
        $requests = MainRequest::fetchRequest($params);

        return response($requests, 200)->header('Content-Type', 'application/json');
    }
}
