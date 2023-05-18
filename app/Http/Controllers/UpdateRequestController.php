<?php

namespace App\Http\Controllers;

use App\Main\Utils;
use App\Models\User;
use Exception;

class UpdateRequestController extends Controller
{
    /**
     * Assign handle person
     * @param App\Models\MdRequest
     * @param Request
     * @param username
     */
    public static function assignRequest($mdRequest, $request, $username)
    {
        try{
            $mdRequest->complete_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('complete_date'))));
            $mdRequest->request_type = $request->input('request_type');
            $mdRequest->handler_id = $request->input('handler');
            $mdRequest->assign_content = $request->input("assign_content");
            $mdRequest->assign_person = $username;
            $mdRequest->status = Utils::DANG_XU_LY;
            $mdRequest->save();
            if ( !empty($request->input('sub_handler')) ) {
                foreach( $request->input('sub_handler') as $sub_id ) {
                    $subUser = User::where('username', $sub_id)->first();
                    try{
                        $mdRequest->sub_handler()->create(['username' => $sub_id, 'name' => $subUser->name]);
                    } catch( Exception $e) {
                        return null;
                    }
                }
            }
            return $mdRequest;
        } catch( Exception $e) {
            return null;
        }
    }

    /**
     * handle request
     * @param App\Models\MdRequest
     * @param Request
     * @param username
     */
    public static function handleRequest($mdRequest, $request, $username)
    {
        try{
            $requestStatus = 'pending';
            // admin or main person
            if ( $mdRequest->handler_id == $username || $mdRequest->handler_id == null ){
                $mdRequest->handle_content = $request->input('handle_content');
                $mdRequest->handler_id = $username;
                $mdRequest->handle_date = date("Y-m-d H:i:s");
                $mdRequest->final_cost = $request->input('final_cost');
                $mdRequest->final_resource = $request->input('final_resource');
                $mdRequest->save();
            } else {
                $mdRequest->sub_handler()->where('username', $username)->update([
                    'status' => Utils::HOAN_THANH,
                    'content' => $request->input('handle_content'),
                ]);
            }
            $subCount = $mdRequest->sub_handler()->where('status', Utils::CHO_XU_LY)->count();
            if ( ($mdRequest->handler_id == $username || $mdRequest->handler_id == null ) && $request->input('status') == Utils::TU_CHOI) {
                $mdRequest->sub_handler()->update([
                    'status' => Utils::HOAN_THANH,
                ]);
            }
            if ( ($mdRequest->handle_date && $subCount == 0) ||  $request->input('status') == Utils::TU_CHOI){
                $mdRequest->status = $request->input('status');
                $mdRequest->save();
                $requestStatus = 'finish';
            }

            return $requestStatus;
        }catch(Exception $e) {
            return null;
        }
    }

    /**
     * return request
     * @param App\Models\MdRequest
     * @param Request
     * @param username
     */
    public static function returnRequest($mdRequest, $request, $username)
    {
        try{
            if ( $mdRequest->handler_id == $username ){
                $mdRequest->handle_content = $request->input('handle_content');
                $mdRequest->status = $request->input('status');
                $mdRequest->save();
                $mdRequest->sub_handler()->delete();
                return true;
            }else{
                return false;
            }
        }catch(Exception $e){return null;}
    }

    /**
     * assign return request
     * @param App\Models\MdRequest
     * @param Request
     * @param username
     */
    public static function assignReturnRequest($mdRequest, $request, $username)
    {
        try{
            // delete sub handler
            $mdRequest->sub_handler()->delete();
            //assign new handler
            $mdRequest->handler_id = $request->input('handler');
            $mdRequest->assign_content = $request->input("assign_content");
            $mdRequest->assign_person = $username;
            $mdRequest->status = Utils::DANG_XU_LY;
            $mdRequest->save();
            if ( !empty($request->input('sub_handler')) ) {
                foreach( $request->input('sub_handler') as $sub_id ) {
                    $subUser = User::where('username', $sub_id)->first();
                    try{
                        $mdRequest->sub_handler()->create(['username' => $sub_id, 'name' => $subUser->name]);
                    } catch( Exception $e) {
                        return null;
                    }
                }
            }
            return $mdRequest;
        } catch( Exception $e) {
            return null;
        }
    }

    /**
     * handle return request
     * @param App\Models\MdRequest
     * @param Request
     * @param username
     */
    public static function handleReturnRequest($mdRequest, $request, $username)
    {
        try{
            // delete sub handler
            $mdRequest->sub_handler()->delete();
            // admin handle request
            $mdRequest->handle_content = $request->input('handle_content');
            $mdRequest->handler_id = $username;
            $mdRequest->handle_date = date("Y-m-d H:i:s");
            $mdRequest->status = $request->input('status');
            $mdRequest->save();

            return true;
        }catch(Exception $e) {
            return null;
        }
    }

    /**
     * handle request
     * @param App\Models\MdRequest
     * @param Request
     * @param username
     */
    public static function extendRequest($mdRequest, $request, $username)
    {
        try{
            if ( $mdRequest->handler_id == $username ){
                $mdRequest->extend_to = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('extend_to'))));
                $mdRequest->extend_content = $request->input('extend_content');
                $mdRequest->status = $request->input('status');
                $mdRequest->extend = Utils::YEU_CAU_GIA_HAN;
                $mdRequest->save();
                return true;
            }else{
                return false;
            }
        }catch(Exception $e) {
            return null;
        }
    }

    /**
     * handle request
     * @param App\Models\MdRequest
     * @param Request
     * @param username
     */
    public static function extendRequestDecide($mdRequest, $request, $username)
    {
        try{
            $extend = $request->input('extend');
            if ( $extend == "A" ){
                $mdRequest->complete_date = $mdRequest->extend_to;
                $mdRequest->status = Utils::DANG_XU_LY;
            } else {
                $mdRequest->status = Utils::DANG_XU_LY;
            }
            $mdRequest->extend = $extend;
            $mdRequest->save();
            return true;
        }catch(Exception $e){return false;}
    }
}
