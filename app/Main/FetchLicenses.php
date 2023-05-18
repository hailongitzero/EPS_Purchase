<?php

namespace App\Main;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use App\Main\Database\Paginator;
use App\Models\MdLicenses;

class FetchLicenses
{
    /**
     * @var MdLicenses
     */
    private $license;

    /**
     * @var Builder|MdLicenses
     */
    private $query;

    /**
     * @param MdLicenses $entry
     */
    public function __construct(MdLicenses $license)
    {
        $this->license = $license;
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
        
        $params['perPage'] = Arr::get($params, 'size');
        $params['page'] = Arr::get($params, 'page');
        $params['with'] = 'category,supplier,manufacturer,uploads,license_seats,license_seats.assigned';
        $paginator = (new Paginator($this->license, $params));
        $this->query = $paginator->query();
        
        if ($filter && $filter[0]['value']) {
            $filterValue = $filter[0]['value'];
            if ($filter[0]['field'] == 'name') {
                $this->query->where('name', 'like', "%$filterValue%");
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