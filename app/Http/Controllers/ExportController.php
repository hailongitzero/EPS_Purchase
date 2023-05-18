<?php

namespace App\Http\Controllers;

use App\Exports\RequestCollection;
use App\Exports\RequestExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * dashboard index
     */
    public function ExportRequest(Request $request)
    {
        $params = $request->all();
        return Excel::download(new RequestExport, 'ycms.xlsx');
    }
}
