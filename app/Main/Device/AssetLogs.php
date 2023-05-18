<?php

namespace App\Main\Device;

use App\Models\MdAssetLogs;
use App\Models\MdStatus;
use Exception;
use Illuminate\Support\Facades\Auth;

class AssetLogs {
    public static function logs($asset)
    {
        try {
            $log = new MdAssetLogs();
            $log->user_id = Auth::user()->id;
            $log->asset_id = $asset->id;
            $log->action_type = MdStatus::find($asset->status_id)->notes;
            $log->checkedout_to = $asset->assigned_to;
            $log->save();

            return $log;
        } catch (Exception $e) {
            return null;
        }
    }
}