<?php

namespace App\Main\Device;

use App\Models\MdRequestables;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class Requestable {
    public static function request($params)
    {
        try {
            $checkout = new MdRequestables();
            $checkout->asset_id = Arr::get($params, 'asset_id');
            $checkout->from_date = Arr::get($params, 'from_date') !== null ? date('Y-m-d', strtotime(str_replace('/', '-', Arr::get($params, 'from_date')))) : null;
            $checkout->to_date = Arr::get($params, 'to_date') !== null ? date('Y-m-d', strtotime(str_replace('/', '-', Arr::get($params, 'to_date')))) : null;
            $checkout->content = Arr::get($params, 'content');
            $checkout->user_id = Auth::user()->id;
            $checkout->save();

            return $checkout;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function accept(MdRequestables $checkout, $params)
    {
        try {
            $checkout->accepted_at = date("Y-m-d H:i:s");
            $checkout->notes = Arr::get($params, 'notes');
            $checkout->save();

            return $checkout;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function denied(MdRequestables $checkout, $params)
    {
        try {
            $checkout->denied_at = date("Y-m-d H:i:s");
            $checkout->notes = Arr::get($params, 'notes');
            $checkout->save();

            return $checkout;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function isExist($params)
    {
        $exist = MdRequestables::whereNull('accepted_at')
            ->whereNull('deleted_at')
            ->where('asset_id', Arr::get($params, 'asset_id'))
            ->where('user_id', Auth::user()->id)->count();
        if ($exist > 0){
            return true;
        } else {
            return false;
        }
    }
}