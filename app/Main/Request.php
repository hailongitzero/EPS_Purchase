<?php

namespace App\Main;

use App\Models\MdRequest;
use App\Models\MdRequestType;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Main\FetchRequests;

class Request {
    /**
     * @var MdRequest
     */
    private $request;

    /**
     * @param FileEntry $entry
     */
    public function __construct(MdRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @var Builder|FileEntry
     */
    private $query;

    /**
     * Get request type
     *
     * @return \Illuminate\Http\Response
     */
    public static function getRequestType()
    {
        return MdRequestType::get();
    }

    public static function export(){
        $requests = MdRequest::with('department','requester','assign','handler','files','type','sub_handler.user','src_tp','fn_src_tp')
            // ->whereBetween('', [(Arr::get($params, 'from_date'))->format('d-m-Y')." 00:00:00",
            //     (Arr::get($params, 'to_date'))->format('d-m-Y')." 23:59:59"])
            ->get();
        return $requests;
    }

    public static function getRequests($param)
    {
        $requests = MdRequest::with('department','requester','assign','handler','files','type','sub_handler.user,src_tp,fn_src_tp');
        $page = Arr::get($param, 'page');
        $size = Arr::get($param, 'size');
        $sort = Arr::get($param, 'sorters');
        $filter = Arr::get($param, 'filters');
        $status = Arr::get($param, 'status');
        $handler = Arr::get($param, 'handler');
        $myRequest = Arr::get($param, 'myRequest');

        if ($myRequest) {
            $requests->where('requester_id', Auth::user()->username);
        }

        if ($handler){   
            $requests->where('handler_id', $handler)->orWhereHas('sub_handler.user', function($query) use($handler) {
                return $query->where('username', $handler);
            });
        }

        if ($status) {
            $requests->whereIn('status', $status);
        }

        if ($filter && $filter[0]['value']) {
            $requests->where('subject', 'like', '%'.$filter[0]['value'].'%');
        }
        if ($sort) {
            $sortField = $sort[0]['field'];
            $sortDir = $sort[0]['dir'];
            $requests->orderBy($sortField, $sortDir);
        } else {
            $requests->orderBy('created_at', 'desc');
        }

        $sReturn = $requests->paginate($size, ['*'], 'page', $page);

        return $sReturn;
    }

    public static function fetchRequest($param) {
        return app(FetchRequests::class)->execute($param);
    }

    public static function getLastActivityRequest(){
        return MdRequest::orderBy('created_at', 'desc')->take(10);
    }
}