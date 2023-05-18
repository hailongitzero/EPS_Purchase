<?php

namespace App\Main;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use App\Main\Database\Paginator;
use App\Models\MdAssets;

class FetchAssets
{
    /**
     * @var MdAssets
     */
    private $assets;

    /**
     * @var Builder|MdAssets
     */
    private $query;

    /**
     * @param MdAssets $entry
     */
    public function __construct(MdAssets $assets)
    {
        $this->assets = $assets;
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
        $requestable = Arr::get($params, 'requestable');
        
        $params['perPage'] = Arr::get($params, 'size');
        $params['page'] = Arr::get($params, 'page');
        $params['with'] = 'model,assigned,assigned.department,creater,status,supplier,department,uploads,maintenances,has_maintenance,requested,user_requested';
        $paginator = (new Paginator($this->assets, $params));
        $this->query = $paginator->query();
        
        if ($filter && $filter[0]['value']) {
            $filterValue = $filter[0]['value'];
            if ($filter[0]['field'] == 'name') {
                $this->query->where('name', 'like', "%$filterValue%")->orWhereHas('assigned', function($q) use($filterValue) {
                    return $q->where('name', 'like', "%$filterValue%");
                });
            }
        }

        if (isset($filter[1]) && $filter[1]['value']) {
            $filterValue = $filter[1]['value'];
            if ($filter[1]['field'] == 'status' && $filterValue) {
                $this->query->where('status_id', '=', $filterValue);
            }
        }
        if (isset($filter[2]) && $filter[2]['value']) {
            $filterValue = $filter[2]['value'];
            if ($filter[2]['field'] == 'dept' && $filterValue) {
                $this->query->where('department_id', '=', $filterValue);
            }
        }

        if ($status) {
            $this->query->where('status_id', $status);
        }

        if ($requestable) {
            $this->query->where('requestable', $requestable);
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