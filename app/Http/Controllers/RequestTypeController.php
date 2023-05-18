<?php

namespace App\Http\Controllers;

use App\Main\RequestType;
use Illuminate\Http\Request;

class RequestTypeController extends Controller
{
     /**
     * Get all request type
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Responsea
     */
    public function getRequestType(){
        $return = RequestType::getType();

        return response($return->toJson(), 200)->header('Content-Type', 'application/json');
    }
}
