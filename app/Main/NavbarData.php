<?php

namespace App\Main;

use App\Models\MdRequest;
use Illuminate\Support\Facades\Auth;

class NavbarData
{


    /**
     * List of side menu items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function notifications() {
        if ( !Auth::user()){
            return null;
        }
        $username = Auth::user()->username;

        $newRequest = MdRequest::where('status', 'A')->count();
        $pendingRequest = MdRequest::whereIn('status', ['B', 'D'])->count();
        $extendRequest = MdRequest::where('status', 'C')->count();
        $returnRequest = MdRequest::where('status', 'E')->count();
        $assignedRequest = MdRequest::whereIn('status', ['B', 'D'])->where(function($query) use($username) {
            $query->whereHas('sub_handler', function($q) use($username) {
                return $q->where('username', $username);
            })->orWhere('handler_id', $username);
        })->count();
        return [
            'newRequest' => array(
                'role' => 2,
                'title' => 'Yêu cầu mới',
                'subtitle' => ' yêu cầu mới chờ xử lý',
                'count' => $newRequest,
                'class' => 'bg-theme-10',
                'tab' => 'new-req-tab',
            ),
            'pendingRequest' => array(
                'role' => 2,
                'title' => 'Đang xử lý',
                'subtitle' => ' yêu cầu đang xử lý',
                'count' => $pendingRequest,
                'class' => 'bg-theme-22',
                'tab' => 'handle-req-tab',
            ),
            'extendRequest' => array(
                'role' => 2,
                'title' => 'Gia hạn',
                'subtitle' => 'yêu cầu cần gia hạn',
                'count' => $extendRequest,
                'class' => 'bg-theme-14',
                'tab' => 'extend-return-req-tab',
            ),
            'returnRequest' => array(
                'role' => 2,
                'title' => 'Chuyển xử lý',
                'subtitle' => 'yêu cầu chuyển xử lý',
                'count' => $returnRequest,
                'class' => 'bg-theme-35',
                'tab' => 'extend-return-req-tab',
            ),
            'assignedRequest' => array(
                'role' => 1,
                'title' => 'Được giao',
                'subtitle' => 'yêu cầu được giao',
                'count' => $assignedRequest,
                'class' => 'bg-theme-23',
                'tab' => 'handle-req-tab',
            ),
        ];
    }
}