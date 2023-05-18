<?php

namespace App\Exports;

use App\Main\Request;
use App\Models\MdRequest;
use Maatwebsite\Excel\Concerns\FromCollection;

class RequestCollection implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Request::export();
    }
}
