<?php

namespace App\Http\Controllers;

use App\Http\Requests\LicenseRequest;
use App\Main\Device\DeployLicense;
use App\Main\Device\InsertLicenses;
use App\Main\Device\LicensesUpload;
use App\Main\Device\UpdateLicenses;
use App\Main\FetchLicenses;
use App\Main\FetchLicenseSeats;
use App\Models\MdAssets;
use App\Models\MdLicenses;
use App\Models\MdLicenseSeats;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LicensesController extends Controller
{
    public function getLicenses(Request $request)
    {
        $params = $request->all();
        $return = app(FetchLicenses::class)->execute($params);
        
        return response($return, 200)->header('Content-Type', 'application/json');
    }

    public function insert(LicenseRequest $request)
    {
        $validated = $request->validated();
        if( $validated ) {
            $license = InsertLicenses::execute($request->all());
            if ( $license ) {
                if ($request->hasFile('attachment')){
                    foreach($request->file('attachment') as $file){
                        $fileInfo = FileUploadController::upload($file, 'licenses');
                        $upload = LicensesUpload::insert($fileInfo['filename'], $fileInfo['basename'], $fileInfo['filepath'], $license->id);
                    }
                }
                return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
            } else {
                return response()->json(['message' => 'Cập nhật không thành công.'], 422)->header('Content-Type', 'application/json');
            }
        }
    }

    public function update(LicenseRequest $request)
    {
        $validated = $request->validated();
        if( $validated ) {
            $license = MdLicenses::find($request->input('id'));
            if ( $license ) {
                $license = UpdateLicenses::execute($license, $request->all());
                if ($request->hasFile('attachment')){
                    foreach($request->file('attachment') as $file){
                        $fileInfo = FileUploadController::upload($file, 'licenses');
                        $upload = LicensesUpload::insert($fileInfo['filename'], $fileInfo['basename'], $fileInfo['filepath'], $license->id);
                    }
                }
                return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
            } else {
                return response()->json(['message' => 'Cập nhật không thành công.'], 422)->header('Content-Type', 'application/json');
            }
        }
    }

    public function delete(Request $request)
    {
        try{
            $license = MdLicenses::find($request->input('id'));
            if ( $license->deleted_at != null ){
                $license->deleted_at = null;
            } else {
                $seats = MdLicenseSeats::where('license_id', $request->input('id'))->whereNull('deleted_at')->count();
                if ($seats > 0){
                    return response()->json(['message' => 'Bản quyền phần mềm đang sử dụng, không thể xoá.'], 422)->header('Content-Type', 'application/json');
                } else {
                    $license->deleted_at = date("Y-m-d H:i:s");
                }
            }
            $license->user_id = Auth::user()->id;
            $license->save();
            return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
        }catch(Exception $e){
            return response()->json(['message' => 'Cập nhật thất bại, vui lòng thử lại.'], 422)->header('Content-Type', 'application/json');
        }
    }

    public function deployDelete(Request $request)
    {
        try{
            $seat = MdLicenseSeats::find($request->input('id'));
            $seat->deleted_at = date("Y-m-d H:i:s");
            $seat->save();
            return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
        }catch(Exception $e){
            return response()->json(['message' => 'Cập nhật thất bại, vui lòng thử lại.'], 422)->header('Content-Type', 'application/json');
        }
    }

    public function checkDeployLicense(Request $request)
    {
        $licenseId = $request->input('id');
        $type = $request->input('type');
        $deployId = $request->input('value');

        $isExist = DeployLicense::isExist($type, $licenseId, $deployId);
        if (!$isExist){
            if (DeployLicense::isRemain($licenseId)){
                if ($type == "user") {
                    $user = User::find($deployId);
                    if ($user){
                        return response()->json(['deploy' => array(
                            'id' => $licenseId,
                            'type' => $type,
                            'deploy_id' => $user->id,
                            'asset_name' => '',
                            'username' => $user->name,
                            'department' => $user->department->department_name,
                        )], 200)->header('Content-Type', 'application/json');
                    } else {
                        return response()->json(['message' => 'Người dùng không tồn tại.'], 422)->header('Content-Type', 'application/json');
                    }
                } else {
                    $asset = MdAssets::find($deployId);
                    if ($asset){
                        return response()->json(['deploy' => array(
                            'id' => $licenseId,
                            'type' => $type,
                            'deploy_id' => $asset->id,
                            'asset_name' => $asset->name,
                            'username' => $asset->assigned ? $asset->assigned->name : '',
                            'department' => $asset->assigned ? $asset->assigned->department->department_name : '',
                        )], 200)->header('Content-Type', 'application/json');
                    } else {
                        return response()->json(['message' => 'Thiết bị không tồn tại.'], 422)->header('Content-Type', 'application/json');
                    }
                }
            } else {
                return response()->json(['message' => 'Đã cấp hết, không thể cấp thêm.'], 422)->header('Content-Type', 'application/json');
            }
        } else {
            return response()->json(['message' => 'Đã cấp, không thể cấp lại.'], 422)->header('Content-Type', 'application/json');
        }
    }

    public function deployLicense(Request $request)
    {
        $licenseDeploy = $request->input('deployList');
        $licenseDeploy  = json_decode($licenseDeploy, true);

        foreach ($licenseDeploy as $deploy) {
            $id = $deploy['id'];
            $type = $deploy['type'];
            $deployId = $deploy['deployId'];
            $isExist = DeployLicense::isExist($type, $id, $deployId);
            if (!$isExist){
                if (DeployLicense::isRemain($id)){
                    DeployLicense::insert($id, $type, $deployId);
                }
            }
        }
        return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
    }

    public function licenseDeployed($id, Request $request)
    {
        $params = $request->all();
        $params['license_id'] = $id;
        $return = app(FetchLicenseSeats::class)->execute($params);
        
        return response($return, 200)->header('Content-Type', 'application/json');
    }
}
