<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Main\Device\AssetLogs;
use App\Main\Device\AssetsUpload;
use App\Main\Device\DeployAssets;
use App\Main\Device\InsertAssets;
use App\Main\Device\UpdateAssets;
use App\Main\Device\ValidateAsset;
use App\Main\FetchAssets;
use App\Models\MdAssets;
use App\Models\MdRequestables;
use Illuminate\Http\Request;

class AssetsController extends Controller
{
    public function getAssets(Request $request)
    {
        $params = $request->all();
        $return = app(FetchAssets::class)->execute($params);
        
        return response($return, 200)->header('Content-Type', 'application/json');
    }

    public function getRequestables(Request $request)
    {
        $params = $request->all();
        $params['status'] = 2;
        $params['requestable'] = 1;

        $return = app(FetchAssets::class)->execute($params);
        
        return response($return, 200)->header('Content-Type', 'application/json');
    }

    public function insert(AssetRequest $request)
    {
        $validated = $request->validated();
        if( $validated ) {
            if (!ValidateAsset::validateSerial($request->input('serial'))){
                return response()->json(['message' => 'Trung số serial, vui lòng nhập lại.'], 422)->header('Content-Type', 'application/json');
            }
            $asset = InsertAssets::execute($request->all());
            if ( $asset ) {
                if ($request->hasFile('attachment')){
                    foreach($request->file('attachment') as $file){
                        $fileInfo = FileUploadController::upload($file, 'assets');
                        $upload = AssetsUpload::insert($fileInfo['filename'], $fileInfo['basename'], $fileInfo['filepath'], $asset->id);
                    }
                }
                if ($request->hasFile('asset_img')) {
                    $imgPath = FileUploadController::uploadDeviceImage($request->file('asset_img'));
                    $asset->image = $imgPath;
                    $asset->save();
                } else if ( $request->input('clone_asset_img') !== null ){
                    $asset->image = $request->input('clone_asset_img');
                    $asset->save();
                }
                return response()->json(['message' => 'Tạo mới thành công.'], 200)->header('Content-Type', 'application/json');
            } else {
                return response()->json(['message' => 'Cập nhật không thành công.'], 422)->header('Content-Type', 'application/json');
            }
        }
    }

    public function update(AssetRequest $request){
        $validated = $request->validated();
        if( $validated ) {
            $asset = MdAssets::find($request->input('id'));
            if ( $asset && ($asset->status_id == 1 || $asset->status_id == 2 || $asset->status_id == 4) ) {
                //thu hoi muon
                if ($asset->status_id == 4) {
                    $assetBorrow = MdRequestables::whereNull('deleted_at')->where('asset_id', $asset->id)->where('user_id', $asset->assigned_to)->first();
                    if ($assetBorrow){
                        $assetBorrow->deleted_at = date("Y-m-d H:i:s");
                        $assetBorrow->save();
                    }
                }
                $asset = UpdateAssets::execute($asset, $request->all());
                if ($request->hasFile('attachment')){
                    foreach($request->file('attachment') as $file){
                        $fileInfo = FileUploadController::upload($file, 'assets');
                        $upload = AssetsUpload::insert($fileInfo['filename'], $fileInfo['basename'], $fileInfo['filepath'], $asset->id);
                    }
                }
                if ($request->hasFile('asset_img')) {
                    $imgPath = FileUploadController::uploadDeviceImage($request->file('asset_img'));
                    $asset->image = $imgPath;
                    $asset->save();
                }
                if ($asset->status_id == 5 || $asset->status_id == 6){
                    $log = AssetLogs::logs($asset);
                }
                return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
            } else {
                return response()->json(['message' => 'Cập nhật không thành công.'], 422)->header('Content-Type', 'application/json');
            }
        }
    }

    public function deploy(Request $request)
    {
        $assets = $request->input('assets');
        $assets  = json_decode($assets, true);
        foreach ($assets as $asset) {
            $masterAsset = MdAssets::find($asset['id']);
            $asset['status_id'] = 3;
            if ($masterAsset){
                if ($masterAsset->quantity > 1){
                    $newAsset = DeployAssets::cloneDeploy($masterAsset, $asset);
                    if ($newAsset){
                        $log = AssetLogs::logs($newAsset);
                    } else {
                        return response()->json(['message' => 'Cập nhật lỗi, vui lòng tải lại trang.'], 422)->header('Content-Type', 'application/json');
                    }
                } else if ($masterAsset->quantity = 1){
                    $newAsset = DeployAssets::deploy($masterAsset, $asset);
                    if ($newAsset){
                        $log = AssetLogs::logs($newAsset);
                    } else {
                        return response()->json(['message' => 'Cập nhật lỗi, vui lòng tải lại trang.'], 422)->header('Content-Type', 'application/json');
                    }
                }
            } else {
                return response()->json(['message' => 'Tài sản không tồn tại, vui lòng tải lại trang.'], 422)->header('Content-Type', 'application/json');
            }
        }
        return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
    }

    public function recall(Request $request){
        $id = $request->input('id');
        
        $asset = MdAssets::find($id);
        if ($asset && ($asset->status_id == 3 || $asset->status_id == 7 )){
            $asset->status_id = 4;
            $asset->save();

            $log = AssetLogs::logs($asset);

            return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
        } else {
            return response()->json(['message' => 'Cập nhật lỗi, vui lòng tải lại trang.'], 422)->header('Content-Type', 'application/json');
        }
    }

    public function recallCancel(Request $request){
        $id = $request->input('id');
        $asset = MdAssets::find($id);
        if ($asset && $asset->status_id == 4){
            $checkBorrow = MdRequestables::whereNull('deleted_at')->where('asset_id', $id)->where('user_id', $asset->assigned_to)->count();
            if ($checkBorrow > 0){
                $asset->status_id = 7;
            } else {
                $asset->status_id = 3;
            }
            $asset->save();

            $log = AssetLogs::logs($asset);

            return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
        } else {
            return response()->json(['message' => 'Cập nhật lỗi, vui lòng tải lại trang.'], 422)->header('Content-Type', 'application/json');
        }
    }
}
