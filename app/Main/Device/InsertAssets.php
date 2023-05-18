<?php

namespace App\Main\Device;

use App\Models\MdAssets;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class InsertAssets {
    public static function execute($params)
    {
        try {
            $asset = new MdAssets();
            $asset->name = Arr::get($params, 'name');
            $asset->asset_tag = Arr::get($params, 'asset-tag');
            $asset->model_id = Arr::get($params, 'model');
            $asset->user_id = Auth::user()->id;
            $asset->serial = Arr::get($params, 'serial');
            $asset->quantity = Arr::get($params, 'quantity');
            $asset->unit = Arr::get($params, 'unit');
            $asset->purchase_cost = Arr::get($params, 'cost');
            $asset->warranty_months = Arr::get($params, 'warranty');
            $asset->status_id = Arr::get($params, 'status');
            $asset->supplier_id = Arr::get($params, 'supplier');
            $asset->order_number = Arr::get($params, 'order_no');
            $asset->purchase_date = Arr::get($params, 'purchase_dt') !== null ? date('Y-m-d', strtotime(str_replace('/', '-', Arr::get($params, 'purchase_dt')))) : null;
            $asset->requestable = Arr::get($params, 'requestable');
            $asset->department_id = Arr::get($params, 'location');
            $asset->notes = Arr::get($params, 'notes');

            $asset->save();

            return $asset;
        } catch (Exception $e) {
            return null;
        }
    }
}