<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModelRequest;
use App\Main\FetchModels;
use App\Models\MdModels;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModelController extends Controller
{
    public function getModels(Request $request)
    {
        $params = $request->all();
        $return = app(FetchModels::class)->execute($params);
        
        return response($return, 200)->header('Content-Type', 'application/json');
    }

    public function insert(ModelRequest $request)
    {
        $validated = $request->validated();
        try{
            $model = new MdModels();
            $model->name = $request->input('name');
            $model->user_id = Auth::user()->id;
            $model->model_number = $request->input('model_number');
            $model->manufacturer_id = $request->input('manufacturer');
            $model->category_id = $request->input('category');
            $model->depreciation_id = $request->input('depreciation');
            $model->eol = $request->input('eol');
            // $model->deprecated_mac_address = $request->input('deprecated_mac_address') == "on" ? 1 : 0;
            // $model->requestable = $request->input('requestable') == "on" ? 1 : 0;
            $model->notes = $request->input('notes');
            $model->save();
    
            if ($request->hasFile('model_img')) {
                $imgPath = FileUploadController::uploadDeviceImage($request->file('model_img'));
                $model->image = $imgPath;
                $model->save();
            }

            return back()->with('success', 'Tạo mới thành công!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error', 'Tạo mới thất bại!']);
        }

    }

    public function update(ModelRequest $request)
    {
        $validated = $request->validated();
        try{
            $model = MdModels::find($request->input('id'));
            $model->name = $request->input('name');
            $model->user_id = Auth::user()->id;
            $model->model_number = $request->input('model_number');
            $model->manufacturer_id = $request->input('manufacturer');
            $model->category_id = $request->input('category');
            $model->depreciation_id = $request->input('depreciation');
            $model->eol = $request->input('eol');
            // $model->deprecated_mac_address = $request->input('deprecated_mac_address') == "on" ? 1 : 0;
            // $model->requestable = $request->input('requestable') == "on" ? 1 : 0;
            $model->notes = $request->input('notes');
            $model->save();
    
            if ($request->hasFile('model_img')) {
                $imgPath = FileUploadController::uploadDeviceImage($request->file('model_img'));
                $model->image = $imgPath;
                $model->save();
            }

            return back()->with('success', 'Cập nhật thành công!');
        } catch (Exception $e) {
            return back()->withErrors(['Cập nhật thất bại!']);
        }
    }

    public function delete(Request $request)
    {
        try{
            $model = MdModels::find($request->input('id'));
            if ( $model->deleted_at != null ){
                $model->deleted_at = null;
            } else {
                $model->deleted_at = date("Y-m-d H:i:s");
            }
            $model->user_id = Auth::user()->id;
            $model->save();
            return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
        }catch(Exception $e){
            return response()->json(['message' => 'Cập nhật thất bại, vui lòng thử lại.'], 422)->header('Content-Type', 'application/json');
        }
    }
}
