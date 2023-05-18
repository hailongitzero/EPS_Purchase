<?php

namespace App\Main;

use App\Models\MdResourceType;

class ResourceType {
    /**
     * Get request type
     *
     * @return \Illuminate\Http\Response
     */
    public static function getType(){
        return MdResourceType::get();
    }
}