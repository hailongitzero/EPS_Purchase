<?php

namespace App\Main\Device;

use App\Models\MdLicenses;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class InsertLicenses {
    public static function execute($params)
    {
        try {
            $license = new MdLicenses();
            $license->name = Arr::get($params, 'name');
            $license->serial = Arr::get($params, 'serial');
            $license->license_name = Arr::get($params, 'license_name');
            $license->license_email = Arr::get($params, 'license_email');
            $license->user_id = Auth::user()->id;
            $license->category_id = Arr::get($params, 'category');
            $license->limit_seats = Arr::get($params, 'limit_seats');
            $license->limit_date = Arr::get($params, 'limit_date');
            $license->purchase_date = Arr::get($params, 'purchase_date') !== null ? date('Y-m-d', strtotime(str_replace('/', '-', Arr::get($params, 'purchase_date')))) : null;
            $license->purchase_cost = Arr::get($params, 'cost');
            $license->manufacturer_id = Arr::get($params, 'manufacturer');
            $license->supplier_id = Arr::get($params, 'supplier');
            $license->order_number = Arr::get($params, 'order_no');
            $license->notes = Arr::get($params, 'notes');

            if (Arr::get($params, 'limit_seats') == 1){
                $license->seats = Arr::get($params, 'seats');
            }
            if (Arr::get($params, 'limit_date') == 1 ){
                $license->expiration_date = date('Y-m-d', strtotime(str_replace('/', '-', Arr::get($params, 'expiration_date'))));
            }

            $license->save();

            return $license;
        } catch (Exception $e) {
            return null;
        }
    }
}