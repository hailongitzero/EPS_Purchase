<?php

namespace App\Http\Controllers;

use App\Http\Requests\maintenanceRequest;
use App\Main\FetchAssets;
use App\Models\MdAssets;
use App\Models\MdAssetUpload;
use App\Models\MdMaintenances;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class MaintenancesController extends Controller
{
    public function getMaintenances(Request $request)
    {
        $params = $request->all();
        $params['status'] = 5;
        $return = app(FetchAssets::class)->execute($params);
        
        return response($return, 200)->header('Content-Type', 'application/json');
    }

    public function mainternanceSetup(maintenanceRequest $request)
    {
        $validate = $request->validated();
        if ($validate){
            $asset = MdAssets::where('id', $request->input('asset_id'))->where('status_id', 5)->first();
            if($asset){
                $id = $request->input('id');
                $checkMtnc = MdMaintenances::where('asset_id', $request->input('asset_id'))->whereIn('status', [1,2])->first();
                if ($id){
                    $maintenance = MdMaintenances::find($id);
                    if ($checkMtnc && $maintenance->id != $checkMtnc->id){
                        return response()->json(['message' => 'Thông tin không chính xác, vui lòng kiểm tra lại.'], 422)
                            ->header('Content-Type', 'application/json');
                    } else {
                        //update maintenance
                        $updateMtn = $this->update($id, $request->all());
                        if ($request->hasFile('attachment')){
                            foreach($request->file('attachment') as $file){
                                $fileInfo = FileUploadController::upload($file, 'maintenances');
                                $upload = $this->insertAttach($fileInfo['filename'], $fileInfo['basename'], $fileInfo['filepath'], $updateMtn->id);
                            }
                        }
                        if ( $updateMtn->status == 3) {
                            // update finish maintenance asset
                            $asset->status_id = 1;
                            $asset->save();
                        }
                        return response()->json(['message' => 'Cập nhật thành công.'], 200)
                            ->header('Content-Type', 'application/json');
                    }
                } else {
                    if ($checkMtnc){
                        return response()->json(['message' => 'Đã tồn tại thông tin bảo trì, không thể tạo thêm.'], 422)
                            ->header('Content-Type', 'application/json');
                    } else {
                        $newMtn = $this->insert($request->all());
                        if ($request->hasFile('attachment')){
                            if ($request->hasFile('attachment')){
                                foreach($request->file('attachment') as $file){
                                    $fileInfo = FileUploadController::upload($file, 'maintenances');
                                    $upload = $this->insertAttach($fileInfo['filename'], $fileInfo['basename'], $fileInfo['filepath'], $newMtn->id);
                                }
                            }
                        }
                        if ( $newMtn->status == 3) {
                            // update finish maintenance asset
                            $asset->status_id = 1;
                            $asset->save();
                        }
                        return response()->json(['message' => 'Cập nhật thành công.'], 200)
                            ->header('Content-Type', 'application/json');
                    }
                }
            } else {
                return response()->json(['message' => 'Không tìm thấy thông tin bảo trì.'], 422)
                            ->header('Content-Type', 'application/json');
            }
        }
    }

    public function insert($params)
    {
        try{
            $maintenance = new MdMaintenances();
            $maintenance->asset_id = Arr::get($params, 'asset_id');
            $maintenance->supplier_id = Arr::get($params, 'supplier_id');
            $maintenance->title = Arr::get($params, 'title');
            $maintenance->notes = Arr::get($params, 'notes');
            $maintenance->is_warranty = Arr::get($params, 'is_warranty');
            $maintenance->start_date = Arr::get($params, 'start_date') !== null ? date('Y-m-d', strtotime(str_replace('/', '-', Arr::get($params, 'start_date')))) : null;
            $maintenance->completion_date = Arr::get($params, 'completion_date') !== null ? date('Y-m-d', strtotime(str_replace('/', '-', Arr::get($params, 'completion_date')))) : null;
            $maintenance->cost = Arr::get($params, 'cost');
            $maintenance->status = Arr::get($params, 'status');
            $maintenance->user_id = Auth::user()->id;

            $maintenance->save();

            return $maintenance;
        } catch (Exception $e){
            return null;
        }
    }

    public function update($id, $params)
    {
        try{
            $maintenance = MdMaintenances::find($id);
            
            $maintenance->supplier_id = Arr::get($params, 'supplier_id');
            $maintenance->title = Arr::get($params, 'title');
            $maintenance->notes = Arr::get($params, 'notes');
            $maintenance->is_warranty = Arr::get($params, 'is_warranty');
            $maintenance->start_date = Arr::get($params, 'start_date') !== null ? date('Y-m-d', strtotime(str_replace('/', '-', Arr::get($params, 'start_date')))) : null;
            $maintenance->completion_date = Arr::get($params, 'completion_date') !== null ? date('Y-m-d', strtotime(str_replace('/', '-', Arr::get($params, 'completion_date')))) : null;
            $maintenance->cost = Arr::get($params, 'cost');
            $maintenance->status = Arr::get($params, 'status');
            $maintenance->user_id = Auth::user()->id;

            $maintenance->save();

            return $maintenance;
        } catch (Exception $e){
            return null;
        }
    }

    public static function insertAttach($filename, $basename, $url, $id)
    {
        try{
            $upload = new MdAssetUpload();
            $upload->user_id = Auth::user()->id;
            $upload->filename = $filename;
            $upload->basename = $basename;
            $upload->url = $url;
            $upload->maintenance_id = $id;
            $upload->save();
            
            return true;
        } catch (Exception $e){
            return $e;
        }
        
    }
}
