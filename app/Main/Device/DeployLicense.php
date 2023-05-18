<?php

namespace App\Main\Device;

use App\Models\MdLicenses;
use App\Models\MdLicenseSeats;
use Exception;
use Illuminate\Support\Facades\Auth;

class DeployLicense {
    public static function isExist($type, $licenseId, $deployId)
    {
        $column = $type == 'user' ? 'assigned_to' : 'asset_id';
        $exist = MdLicenseSeats::where('license_id', $licenseId)->whereNull('deleted_at')->where($column, $deployId)->count();

        return $exist == 0 ? false : true;
    }

    public static function isRemain($licenseId){
        $license = MdLicenses::whereNull('deleted_at')->find($licenseId);
        if ( $license ){
            if ($license->limit_seats == 1) {
                if ($license->remain > 0){
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public static function insert($id, $type, $deployId)
    {
        try{
            $seat = new MdLicenseSeats();

            $seat->license_id = $id;
            $seat->user_id = Auth::user()->id;
            if ($type == 'user'){
                $seat->assigned_to = $deployId;
            } else {
                $seat->asset_id = $deployId;
            }

            $seat->save();
        } catch(Exception $e){
        }
    }
}