<?php

namespace App\Main\Device;

use App\Models\MdAssetUpload;
use Exception;
use Illuminate\Support\Facades\Auth;

class LicensesUpload {
    public static function insert($filename, $basename, $url, $licenseID)
    {
        try{
            $upload = new MdAssetUpload();
            $upload->user_id = Auth::user()->id;
            $upload->filename = $filename;
            $upload->basename = $basename;
            $upload->url = $url;
            $upload->license_id = $licenseID;
            $upload->save();
            
            return true;
        } catch (Exception $e){
            return $e;
        }
        
    }
}