<?php

namespace App\Http\Controllers;

use App\Models\MdRequest;
use Exception;

class CreateRequestController extends Controller
{
    /**
     * create new request primary key
     */
    public static function createReqKey() {
        $maxKey = MdRequest::max('request_id');

        if ($maxKey === NULL) {
            $request_id = 'R000001';
        } else {
            $request_id = CommonController::createPrimaryKey($maxKey, 'R', 7);
        }
        return $request_id;
    }


    public static function create($request){
        try {
            $newRequest = new MdRequest();

            $newRequest->request_id = CreateRequestController::createReqKey();
            $newRequest->requester_id = $request->input('requester');
            $newRequest->department_id = $request->input('department');
            $newRequest->priority = $request->input('priority');
            $newRequest->subject = $request->input('subject');
            $newRequest->content = $request->input('content');
            $newRequest->request_type = $request->input('requestTp');
            $newRequest->cost = $request->input('cost');
            $newRequest->resource = $request->input('resource');
            $newRequest->final_cost = $request->input('cost');
            $newRequest->final_resource = $request->input('resource');
            $newRequest->cc_email = $request->input('ccEmail');
            $newRequest->complete_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('completeDate'))));

            $newRequest->save();

            return $newRequest;
        } catch (Exception $e) {
            return null;
        }
    }
}
