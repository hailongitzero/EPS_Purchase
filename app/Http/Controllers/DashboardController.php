<?php

namespace App\Http\Controllers;

use App\Main\Dashboard;
use App\Main\RequestType;
use App\Main\Users;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * get total request of department by period time
     * @param Illuminate\Http\Request
     * @return response
     */
    public function getDepartmentRequestPeriod(Request $request)
    {
        $type = $request->input('type');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $result = Dashboard::totalDepartmentRequestByPeriod($type, $fromDate, $toDate);
        $sum = Dashboard::sumDepartmentRequestByPeriod($type, $fromDate, $toDate);

        return response(['list' => $result, 'sum' => $sum], 200)->header('Content-Type', 'application/json');
    }

    /**
     * get total request group period by time
     * @param Illuminate\Http\Request
     * @return response
     */
    public function getRequestGroupByPeriod(Request $request)
    {
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        if ($request->has('reqType')) {
            $reqTp = $request->input('reqType');
        }
        $result = Dashboard::getRequestGroupByPeriod($fromDate, $toDate);
        $total = Dashboard::getSumRequestGroupByPeriod($fromDate, $toDate);

        return response(['result' => $result, 'sum' => $total], 200)->header('Content-Type', 'application/json');
    }

    /**
     * get total request group period by time
     * @param Illuminate\Http\Request
     * @return response
     */
    public function getLatestActivity(Request $request)
    {
        $result = Dashboard::getLatestActiveRequest();
        return response(['result' => $result], 200)->header('Content-Type', 'application/json');
    }
}
