<?php

namespace App\Main;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use App\Main\Database\Paginator;
use App\Models\MdRequestables;
use Illuminate\Support\Facades\DB;

class FetchAssetRequestables
{
    /**
     * @var MdRequestables
     */
    private $assets;

    /**
     * @var Builder|MdRequestables
     */
    private $query;

    /**
     * @param MdRequestables $entry
     */
    public function __construct(MdRequestables $requestable)
    {
        $this->requestable = $requestable;
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
        $inprogress = Arr::get($params, 'inprogress');
        
        $params['perPage'] = Arr::get($params, 'size');
        $params['page'] = Arr::get($params, 'page');
        $params['with'] = 'requester,requester.department,asset,asset.model,asset.department';
        $paginator = (new Paginator($this->requestable, $params));
        $this->query = $paginator->query();

        if ($inprogress) {
            $this->query->whereNull('accepted_at')->whereNull('denied_at');
        }
        
        if ($filter && $filter[0]['value']) {
            $filterValue = $filter[0]['value'];
            if ($filter[0]['field'] == 'asset_name') {
                $this->query->whereHas('asset', function($q) use($filterValue) {
                    return $q->where('name', 'like', "%$filterValue%");
                });
            }
        }

        if (isset($filter[1]) && $filter[1]['value']) {
            $filterValue = $filter[1]['value'];
            if ($filter[1]['field'] == 'requester' && $filterValue) {
                $this->query->whereHas('requester', function($q) use($filterValue) {
                    return $q->where('name', 'like', "%$filterValue%");
                });
            }
        }

        if ($sort) {
            $sortField = $sort[0]['field'];
            $sortDir = $sort[0]['dir'];
            $this->query->orderBy($sortField, $sortDir);
        } else {
            $this->query->orderBy('created_at', 'asc');
        }

        $results = $paginator->paginate()->toJson();

        return $results;
    }
}