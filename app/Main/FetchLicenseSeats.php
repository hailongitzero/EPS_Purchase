<?php

namespace App\Main;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use App\Main\Database\Paginator;
use App\Models\MdLicenseSeats;

class FetchLicenseSeats
{
    /**
     * @var MdLicenseSeats
     */
    private $seats;

    /**
     * @var Builder|MdLicenseSeats
     */
    private $query;

    /**
     * @param MdLicenseSeats $entry
     */
    public function __construct(MdLicenseSeats $seats)
    {
        $this->seats = $seats;
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
        $licenseId = Arr::get($params, 'licenseId');
        
        $params['perPage'] = Arr::get($params, 'size');
        $params['page'] = Arr::get($params, 'page');
        $params['with'] = 'license,assigned,assigned.department,asset,asset.model,asset.assigned,asset.assigned.department';
        $paginator = (new Paginator($this->seats, $params));
        $this->query = $paginator->query();
        
        if ($licenseId) {
            $this->query->where('license_id', $licenseId);
        }
        $this->query->whereNull('deleted_at');

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
            if ($filter[1]['field'] == 'user_name' && $filterValue) {
                $this->query->whereHas('assigned', function($q) use($filterValue) {
                    return $q->where('name', 'like', "%$filterValue%");
                });
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