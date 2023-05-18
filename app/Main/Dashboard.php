<?php

namespace App\Main;

use App\Models\MdRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Dashboard {

    /**
     * get total request
     */
    public static function totalRequest()
    {
        return MdRequest::count();
    }

    public static function totalDailyRequest(){
        return MdRequest::whereDate('created_at', date('Y-m-d'))->count();
    }

    /**
     * get total my request
     */
    public static function totalMyRequest()
    {
        return MdRequest::where('requester_id', Auth::user()->username)->count();
    }

    /**
     * get total request of department by period
     */
    public static function totalDepartmentRequestByPeriod($type, $fromDate, $toDate)
    {
        $dateCond = "AND DATE(req.created_at) BETWEEN '$fromDate' AND '$toDate' ";
        if ($type) {
            $dateCond .= "AND req.request_type = '{$type}' ";
        }
        $query = "SELECT
                        dept.short_name AS label,
                        COUNT(req.request_id) AS data
                    FROM
                        departments dept
                    LEFT JOIN requests req ON
                        dept.department_id = req.department_id ";
        $query .= $dateCond;
        $query .= "GROUP BY
                        dept.short_name";
        $result = DB::select(DB::raw($query));

        return $result;
    }

    /**
     * get sum request of department by period
     */
    public static function sumDepartmentRequestByPeriod($type, $fromDate, $toDate)
    {
        $dateCond = "AND DATE(created_at) BETWEEN '$fromDate' AND '$toDate' ";
        if ($type) {
            $dateCond .= "AND request_type = '{$type}' ";
        }
        $query = "SELECT COUNT(request_id) AS cnt FROM requests WHERE 1 " .$dateCond;
        $result = DB::select(DB::raw($query));

        return $result;
    }

    /**
     * get total of request goup
     */
    public static function getRequestGroupByPeriod($fromDate, $toDate)
    {
        DB::enableQueryLog();
        $dateCond = "AND DATE(created_at) BETWEEN '$fromDate' AND '$toDate' ";
        $query = "SELECT '1' as type, count(request_id) as cnt FROM `requests` WHERE status = 'A' $dateCond
                    UNION
                    SELECT '2' as type, count(request_id) as cnt FROM `requests` WHERE status in ('B','C','D','E') $dateCond
                    UNION
                    SELECT '3' as type, count(request_id) as cnt FROM `requests` WHERE status = 'F' $dateCond
                    UNION
                    SELECT '4' as type, count(request_id) as cnt FROM `requests` WHERE status = 'X' $dateCond";
        $result = DB::select(DB::raw($query));
        // dd(DB::getQueryLog());
        return $result;
    }

    /**
     * get sum of request goup
     */
    public static function getSumRequestGroupByPeriod($fromDate, $toDate)
    {
        $dateCond = "AND DATE(created_at) BETWEEN '$fromDate' AND '$toDate' ";

        $query = "SELECT COUNT(*) AS total FROM `requests` WHERE 1 ".$dateCond;
        $result = DB::select(DB::raw($query));

        return $result;
    }

    public static function getLatestActiveRequest()
    {
        $result = MdRequest::with('department','requester','assign','handler','files','type','sub_handler')->orderBy('updated_at', 'desc')->take(10)->get();
        $retArr = array();
        foreach($result as $res) {
            if ($res->status == Utils::YEU_CAU_MOI){
                try{
                    array_push($retArr, array(
                        'subject' => $res->subject,
                        'name' => $res->requester->name,
                        'photo' => $res->requester->photo,
                        'active' => 'Tạo yêu cầu mới',
                        'time' => $res->created_at,
                        'tab' => 'new-req-tab',
                        'class' => 'bg-theme-9',
                    ));
                } catch(Exception $e){}
            } else if ($res->status == Utils::TIEP_NHAN) {
                try{
                    array_push($retArr, array(
                        'subject' => $res->subject,
                        'name' => $res->assign->name,
                        'photo' => $res->assign->photo,
                        'active' => 'Tiếp nhận yêu cầu',
                        'time' => $res->updated_at,
                        'tab' => 'handle-req-tab',
                        'class' => 'bg-theme-10',
                    ));
                } catch(Exception $e){}
            } else if ($res->status == Utils::DANG_XU_LY) {
                try{
                    array_push($retArr, array(
                        'subject' => $res->subject,
                        'name' => $res->handler->name,
                        'photo' => $res->handler->photo,
                        'active' => 'Xử lý yêu cầu',
                        'time' => $res->updated_at,
                        'tab' => 'handle-req-tab',
                        'class' => 'bg-theme-22',
                    ));
                } catch(Exception $e){}
            } else if ($res->status == Utils::GIA_HAN) {
                try{
                    array_push($retArr, array(
                        'subject' => $res->subject,
                        'name' => $res->handler->name,
                        'photo' => $res->handler->photo,
                        'active' => 'Gia hạn xử lý',
                        'time' => $res->updated_at,
                        'tab' => 'extend-return-req-tab',
                        'class' => 'bg-theme-26',
                    ));
                } catch(Exception $e){}
            } else if ($res->status == Utils::CHUYEN_XU_LY) {
                try{
                    array_push($retArr, array(
                        'subject' => $res->subject,
                        'name' => $res->handler->name,
                        'photo' => $res->handler->photo,
                        'active' => 'Chuyển xử lý yêu cầu',
                        'time' => $res->updated_at,
                        'tab' => 'extend-return-req-tab',
                        'class' => 'bg-theme-14',
                    ));
                } catch(Exception $e){}
            } else if ($res->status == Utils::HOAN_THANH) {
                try{
                    array_push($retArr, array(
                        'subject' => $res->subject,
                        'name' => $res->handler->name,
                        'photo' => $res->handler->photo,
                        'active' => 'Hoàn thành xử lý yêu cầu',
                        'time' => $res->updated_at,
                        'tab' => 'completed-req-tab',
                        'class' => 'bg-theme-23',
                    ));
                } catch(Exception $e){}
            } else if ($res->status == Utils::TU_CHOI) {
                try{
                    array_push($retArr, array(
                        'subject' => $res->subject,
                        'name' => $res->handler->name,
                        'photo' => $res->handler->photo,
                        'active' => 'Từ chối xử lý',
                        'time' => $res->updated_at,
                        'tab' => 'completed-req-tab',
                        'class' => 'bg-theme-35',
                    ));
                } catch(Exception $e){}
            }
        }
        return $retArr;
    }
}