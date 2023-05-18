<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Responsea
     */
    public static function createPrimaryKey($oldKey, $prefix, $length)
    {
        $prefixLength = strlen($prefix);
        $keyNum = substr($oldKey, $prefixLength, ($length - $prefixLength));
        $newNum = (int)$keyNum + 1;
        $newKey = $prefix;
        $zero = $length - $prefixLength - strlen($newNum);
        for ($i = 0; $i < $zero; $i++) {
            $newKey .= '0';
        }

        $newKey .= $newNum;
        return $newKey;
    }
}
