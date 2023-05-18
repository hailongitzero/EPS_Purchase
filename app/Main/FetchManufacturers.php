<?php

namespace App\Main;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use App\Main\Database\Paginator;
use App\Models\MdManufacturers;

class FetchManufacturers
{
    /**
     * @var MdManufacturers
     */
    private $manufacturers;

    /**
     * @var Builder|MdManufacturers
     */
    private $query;

    /**
     * @param MdManufacturers $entry
     */
    public function __construct(MdManufacturers $manufacturers)
    {
        $this->manufacturers = $manufacturers;
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
        
        $params['perPage'] = $size = Arr::get($params, 'size');
        $params['page'] = Arr::get($params, 'page');
        // $params['with'] = 'models';
        $paginator = (new Paginator($this->manufacturers, $params));
        $this->query = $paginator->query();
        
        if ($filter && $filter[0]['value']) {
            $filterValue = $filter[0]['value'];
            if ($filter[0]['field'] == 'name') {
                $this->query->where('name', 'like', "%$filterValue%");
            }
        }

        if (isset($filter[1]) && $filter[1]['value']) {
            $filterValue = $filter[1]['value'];
            if ($filter[1]['field'] == 'status' && $filterValue) {
                if ($filterValue == "1") {
                    $this->query->whereNull('deleted_at');
                } else if ($filterValue == "2") {
                    $this->query->whereNotNull('deleted_at');
                }
                
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