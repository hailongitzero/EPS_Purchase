<?php

namespace App\Main\Device;

use App\Models\MdAssets;
use Exception;

class ValidateAsset {
    public static function validateSerial($serial)
    {
        try {
            if ( isset($serial) ){
                $serialCnt = MdAssets::where('serial', $serial)->count();
                if ( $serialCnt > 0){
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}