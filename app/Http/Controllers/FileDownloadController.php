<?php

namespace App\Http\Controllers;

use App\Models\MdAssetUpload;
use App\Models\MdFileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileDownloadController extends Controller
{
    public function downloadAttachment($id)
    {
        $file = MdFileUpload::where('file_id', $id)->first();
        return response()->download(public_path('storage/attachment/').$file->store_file_name, $file->file_name);
    }

    public function downloadAssetAttachment($id)
    {
        $file = MdAssetUpload::where('id', $id)->first();
        return response()->download(public_path('storage/assets/').$file->basename, $file->filename);
    }

    public function downloadMaintenanceachment($id)
    {
        $file = MdAssetUpload::where('id', $id)->first();
        return response()->download(public_path('storage/maintenances/').$file->basename, $file->filename);
    }

    public function downloadLicenseAttachment($id)
    {
        $file = MdAssetUpload::where('id', $id)->first();
        return response()->download(public_path('storage/licenses/').$file->basename, $file->filename);
    }
}
