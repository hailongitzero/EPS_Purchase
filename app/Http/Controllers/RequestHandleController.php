<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignRequest;
use App\Http\Requests\ExtendRequest;
use App\Main\Utils;
use App\Models\MdRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestHandleController extends Controller
{
    /**
     * Assign request to moderator
     * @param Illuminate\Http\Request
     * @return Response
     */
    public function assignRequest(AssignRequest $request)
    {
        $username = Auth::user()->username;
        $validated = $request->validated();
        $sSuccess = true;
        $mesage = "";
        if ($validated) {
            DB::beginTransaction();
            $assignRequest = MdRequest::where('request_id', $request->input('request_id'))->first();
            if ( $assignRequest->status != Utils::YEU_CAU_MOI ){
                return response(['message' => 'Trạng thái yêu cầu không đúng, vui lòng tải lại trang'], 422)->header('Content-Type', 'application/json');
            }
            $sMdRequest = UpdateRequestController::assignRequest($assignRequest, $request, $username);
            if ( $sMdRequest ){
                try{
                    // attach request file
                    FileUploadController::attachFile($request, $sMdRequest->request_id);
                    SendEmailController::assignMail($sMdRequest);
                } catch (Exception $e) {
                    $sSuccess = false;
                    $mesage = "File đính kèm lỗi. Vui lòng thử lại.";
                }
            } else {
                $sSuccess = false;
                $mesage = "Chuyển xử lý thất bại. Vui lòng thử lại.";
            }
        }
        if ($sSuccess) {
            DB::commit();
            return response(['message' => 'Chuyển xử lý thành công.'], 200)->header('Content-Type', 'application/json');
        } else {
            DB::rollBack();
            return response(['message' => $mesage, 'errors' => ''], 422)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Decide extend request
     * @param Illuminate\Http\Request
     * @return Response
     */
    public function handleRequest(Request $request){
        $username = Auth::user()->username;
        $handleRequest = MdRequest::with('sub_handler')->where('request_id', $request->input('request_id'))->first();
        if ( $handleRequest->status == Utils::HOAN_THANH || $handleRequest->status == Utils::TU_CHOI ){
            return response(['message' => 'Trạng thái yêu cầu không đúng, vui lòng làm mới dữ liệu.'], 500)->header('Content-Type', 'application/json');
        }
        DB::beginTransaction();
        $result = UpdateRequestController::handleRequest($handleRequest, $request, $username);
        if ( $result ) {
            try{
                // attach request file
                FileUploadController::attachFile($request, $handleRequest->request_id);
                if ( $result == "finish") {
                    SendEmailController::completeMail($handleRequest);
                }
            } catch (Exception $e) {
                DB::rollBack();
                return response(['message' => 'File đính kèm lỗi. Vui lòng thử lại.', 'errors' => 'File đính kèm lỗi. Vui lòng thử lại.'], 500)->header('Content-Type', 'application/json');
            }
            DB::commit();
            return response(['message' => 'Cập nhật thành công.', 'status' => $result], 200)->header('Content-Type', 'application/json');
        } else {
            DB::rollBack();
            return response(['message' => 'Cập nhật thất bại, vui lòng thử lại.', 'errors' => 'Cập nhật thất bại, vui lòng thử lại.'], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Decide extend request
     * @param Illuminate\Http\Request
     * @return Response
     */
    public function returnRequest(Request $request){
        $username = Auth::user()->username;
        $handleRequest = MdRequest::with('sub_handler')->where('request_id', $request->input('request_id'))->first();
        if ( $handleRequest->status != Utils::TIEP_NHAN || $handleRequest->status != Utils::DANG_XU_LY){
            DB::beginTransaction();
            $result = UpdateRequestController::returnRequest($handleRequest, $request, $username);
            if ( $result ) {
                try{
                    SendEmailController::returnMail($handleRequest);
                } catch (Exception $e) {}
                DB::commit();
                return response(['message' => 'Cập nhật thành công.', 'status' => $result], 200)->header('Content-Type', 'application/json');
            } else {
                DB::rollBack();
                return response(['message' => 'Cập nhật thất bại, vui lòng thử lại.', 'errors' => 'Cập nhật thất bại, vui lòng thử lại.'], 500)->header('Content-Type', 'application/json');
            }
        } else {
            return response(['message' => 'Trạng thái yêu cầu không đúng, vui lòng làm mới dữ liệu.'], 422)->header('Content-Type', 'application/json');
        }
    }

    public function assignReturnRequest(Request $request)
    {
        $username = Auth::user()->username;
        $sSuccess = true;
        $mesage = "";
        DB::beginTransaction();
            $assignRequest = MdRequest::with('handler', 'sub_handler')->find($request->input('request_id'));
            if ( $assignRequest->status != Utils::CHUYEN_XU_LY ){
                return response(['message' => 'Trạng thái yêu cầu không đúng, vui lòng làm mới dữ liệu.'], 500)->header('Content-Type', 'application/json');
            }
            $sMdReturn = UpdateRequestController::assignReturnRequest($assignRequest, $request, $username);
            if ( $sMdReturn ){
                try{
                    SendEmailController::assignMail($sMdReturn);
                } catch (Exception $e) {}
            } else {
                $sSuccess = false;
                $mesage = "Chuyển xử lý thất bại. Vui lòng thử lại.";
            }
        if ($sSuccess) {
            DB::commit();
            return response(['message' => 'Chuyển xử lý thành công.'], 200)->header('Content-Type', 'application/json');
        } else {
            DB::rollBack();
            return response(['message' => $mesage, 'errors' => ''], 500)->header('Content-Type', 'application/json');
        }
    }

    public function handleReturnRequest(Request $request)
    {
        $username = Auth::user()->username;
        $handleRequest = MdRequest::with('sub_handler')->where('request_id', $request->input('request_id'))->first();
        if ( $handleRequest->status == Utils::HOAN_THANH || $handleRequest->status == Utils::TU_CHOI ){
            return response(['message' => 'Trạng thái yêu cầu không đúng, vui lòng làm mới dữ liệu.'], 500)->header('Content-Type', 'application/json');
        }
        DB::beginTransaction();
        $result = UpdateRequestController::handleReturnRequest($handleRequest, $request, $username);
        if ( $result ) {
            try{
                SendEmailController::completeMail($handleRequest);
            } catch (Exception $e) {}
            DB::commit();
            return response(['message' => 'Cập nhật thành công.', 'status' => $result], 200)->header('Content-Type', 'application/json');
        } else {
            DB::rollBack();
            return response(['message' => 'Cập nhật thất bại, vui lòng thử lại.', 'errors' => 'Cập nhật thất bại, vui lòng thử lại.'], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Decide extend request
     * @param Illuminate\Http\Request
     * @return Response
     */
    public function extendRequest(Request $request){
        $username = Auth::user()->username;
        $handleRequest = MdRequest::with('sub_handler')->where('request_id', $request->input('request_id'))->first();

        if ( $handleRequest->status == Utils::TIEP_NHAN || $handleRequest->status == Utils::DANG_XU_LY ){
            DB::beginTransaction();
            $result = UpdateRequestController::extendRequest($handleRequest, $request, $username);
            if ( $result ) {
                try{
                    SendEmailController::extendMail($handleRequest);
                } catch (Exception $e) {}
                DB::commit();
                return response(['message' => 'Cập nhật thành công.', 'status' => $result], 200)->header('Content-Type', 'application/json');
            } else {
                DB::rollBack();
                return response(['message' => 'Cập nhật thất bại, vui lòng thử lại.', 'errors' => 'Cập nhật thất bại, vui lòng thử lại.'], 500)->header('Content-Type', 'application/json');
            }
        } else {
            return response()->json(['message' => 'Trạng thái yêu cầu không đúng, vui lòng làm mới dữ liệu.'], 422)->header('Content-Type', 'application/json');
        }
    }

    /**
     * request extend
     * @param Illuminate\Http\Request
     * @return Response
     */
    public function extendRequestDecide(ExtendRequest $request){
        $username = Auth::user()->username;
        $extendRequest = MdRequest::with('sub_handler')->where('request_id', $request->input('request_id'))->first();
        if ( $extendRequest->status != Utils::GIA_HAN){
            return response(['message' => 'Trạng thái yêu cầu không đúng, vui lòng làm mới dữ liệu.'], 500)->header('Content-Type', 'application/json');
        }
        DB::beginTransaction();
        $result = UpdateRequestController::extendRequestDecide($extendRequest, $request, $username);
        if ( $result ) {
            try{
                SendEmailController::extendResultMail($extendRequest);
            } catch (Exception $e) {}
            DB::commit();
            return response(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
        } else {
            DB::rollBack();
            return response(['message' => 'Cập nhật thất bại, vui lòng thử lại.'], 500)->header('Content-Type', 'application/json');
        }
    }
}
