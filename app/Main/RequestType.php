<?php

namespace App\Main;

use App\Models\MdRequestType;

class RequestType {
    /**
     * Get request type
     *
     * @return \Illuminate\Http\Response
     */
    public static function getType(){
        return MdRequestType::get();
    }
}