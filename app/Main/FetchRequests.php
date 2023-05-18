<?php

namespace App\Main;

use App\Models\MdRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Main\Database\Paginator;
use App\Models\MdRequestSubPerson;
use Illuminate\Support\Facades\DB;

class FetchRequests
{
    /**
     * @var MdRequest
     */
    private $request;

    /**
     * @var Builder|MdRequest
     */
    private $query;

    /**
     * @param MdRequest $entry
     */
    public function __construct(MdRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Fetch all file entries matching specified params.
     *
     * @param array $params
     * @return array
     */
    public function execute($params)
    {
        $sort = Arr::get($params, 'sorters');
        $filter = Arr::get($params, 'filters');
        $status = Arr::get($params, 'status');
        $role = Arr::get($params, 'role');
        $handler = Arr::get($params, 'handler');
        $myRequest = Arr::get($params, 'myRequest');
        
        $params['perPage'] = $size = Arr::get($params, 'size');
        $params['page'] = Arr::get($params, 'page');
        $params['with'] = 'department,requester,assign,handler,files,type,sub_handler.user,src_tp,fn_src_tp';
        $paginator = (new Paginator($this->request, $params));
        $this->query = $paginator->query();
        
        // fetch only files, if we need recent entries
        if ($myRequest) {
            $this->query->where('requester_id', Auth::user()->username);
        }

        if ($status) {
            $this->query->whereIn('status', $status);
            if ( $role == Utils::PHO_QUAN_LY && ( in_array(Utils::TIEP_NHAN, $status) || in_array(Utils::DANG_XU_LY, $status) )) {
                $this->query->where(function($query) use($handler) {
                    $query->whereHas('sub_handler', function($q) use($handler) {
                        return $q->where('username', $handler)
                            ->where('status', Utils::CHO_XU_LY);
                    })->orWhere('handler_id', $handler);
                });
            } else if ($role == Utils::PHO_QUAN_LY && ( in_array(Utils::CHUYEN_XU_LY, $status) || in_array(Utils::GIA_HAN, $status) )) {
                $this->query->where('handler_id', $handler);
            } else {
                $this->query->whereIn('status', $status);
            }
        }

        if ($handler && $role == Utils::PHO_QUAN_LY) {
            $this->query->where(function($query) use($handler) {
                $query->whereHas('sub_handler', function($q) use($handler) {
                    return $q->where('username', $handler);
                })->orWhere('handler_id', $handler);
            });
        }
        if ($filter && $filter[0]['value']) {
            $filterValue = $filter[0]['value'];
            if ($filter[0]['field'] == 'subject') {
                $this->query->where('subject', 'like', "%$filterValue%");
            } else if ($filter[0]['field'] == 'id') {
                $this->query->where('request_id', '=', "$filterValue");
            }
        }

        if (isset($filter[1]) && $filter[1]['value']) {
            $filterValue = $filter[1]['value'];
            if ($filter[1]['field'] == 'dept' && $filterValue) {
                $this->query->where('department_id', '=', $filterValue);
            }
        }

        if (isset($filter[2]) && $filter[2]['value']) {
            $filterValue = $filter[2]['value'];
            if ($filter[2]['field'] == 'status' && $filterValue) {
                $this->query->where('status', '=', $filterValue);
            }
        }

        if ($sort) {
            $sortField = $sort[0]['field'];
            $sortDir = $sort[0]['dir'];
            $this->query->orderBy($sortField, $sortDir);
        } else {
            $this->query->orderBy('created_at', 'desc');
        }

        $results = $paginator->paginate()->toJson();

        return $results;
    }
}