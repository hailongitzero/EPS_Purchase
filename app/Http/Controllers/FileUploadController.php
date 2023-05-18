<?php

namespace App\Http\Controllers;

use App\Models\MdFileUpload;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    
    /**
     * upload image from CKEditor
     */
    public function ckUploadImage(Request $request){
        if ($request->hasFile('upload')){
            $files = $request->file('upload');
            $allowedfileExtension=['jpg','png'];
            $path = '';
            $filename = strtotime(date("D M d, Y G:i")) . '_' . $files->getClientOriginalName();

            $fileExtension = strtolower($files->getClientOriginalExtension());
            $checkExtension = in_array($fileExtension, $allowedfileExtension);

            if ($checkExtension){
                $path = $files->storeAs('public/ckeditor', $filename);
            }
            $return = array(
                "uploaded"=> 1,
                "fileName"=> $filename,
                "url" => Storage::url('ckeditor/'.$filename),
            );
            return response(json_encode($return), 200)->header('Content-Type', 'application/json');
        } else {
            $return = array(
                "uploaded"=> 0,
                "fileName"=> '',
                "url" => Storage::url(''),
            );
            return response(json_encode($return), 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * store attachment file
     * 
     * @param Illuminate\Http\Request
     * @param app\Http\Model\MdRequest
     * @return array
     */
    public static function attachFile($request, $reqId) {
        if ( $request->hasFile('attachment') ) {
            $files = $request->file('attachment');

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $path = $file->store('public/attachment');
                $storeFileName = pathinfo($path)['basename'];
                $filePath = 'storage/attachment/'.$filename;

                FileUploadController::insertAttachment($reqId, $filename, $storeFileName, $filePath);
            }
        }
    }

    public static function insertAttachment($reqId, $filename, $storeFileName, $path){
        try{
            $attachFile = New MdFileUpload();
            $attachFile->request_id = $reqId;
            $attachFile->file_name = $filename;
            $attachFile->store_file_name = $storeFileName;
            $attachFile->store_url = $path;
            $attachFile->save();
        } catch (Exception $e) {}
    }

    public static function uploadDeviceImage($file){
        $path = $file->store('public/device');
        $storeFileName = pathinfo($path)['basename'];
        $filePath = 'storage/device/'.$storeFileName;
        return $filePath;
    }

    /**
     * store attachment file
     * 
     * @param Illuminate\Http\Request
     * @param app\Http\Model\MdRequest
     * @return array
     */
    public static function upload($file, $location) {
        $filename = $file->getClientOriginalName();
        $fileExtension = strtolower($file->getClientOriginalExtension());
        $path = $file->store('public/'.$location);
        $storeFileName = pathinfo($path)['basename'];
        $filePath = 'storage/'.$location.'/'.$storeFileName;
        return array(
            'filename' => $filename,
            'basename' => $storeFileName,
            'filepath' => $filePath,
        );
    }
}
