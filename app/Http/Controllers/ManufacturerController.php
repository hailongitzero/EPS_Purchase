<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManufacturerRequest;
use App\Main\FetchManufacturers;
use App\Models\MdManufacturers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManufacturerController extends Controller
{
    public function getManufacturers(Request $request)
    {
        $params = $request->all();
        $return = app(FetchManufacturers::class)->execute($params);
        
        return response($return, 200)->header('Content-Type', 'application/json');
    }

    public function insert(ManufacturerRequest $request)
    {
        $validated = $request->validated();
        try{
            $manufacturer = new MdManufacturers();
            $manufacturer->name = $request->input('name');
            $manufacturer->user_id = Auth::user()->id;
            $manufacturer->url = $request->input('url');
            $manufacturer->support_url = $request->input('support_url');
            $manufacturer->support_phone = $request->input('support_phone');
            $manufacturer->support_email = $request->input('support_email');
            $manufacturer->save();
    
            if ($request->hasFile('manufacturer_img')) {
                $imgPath = FileUploadController::uploadDeviceImage($request->file('manufacturer_img'));
                $manufacturer->image = $imgPath;
                $manufacturer->save();
            }

            return back()->with('success', 'Tạo mới thành công!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error', 'Tạo mới thất bại!']);
        }

    }

    public function update(ManufacturerRequest $request)
    {
        $validated = $request->validated();
        try{
            $manufacturer = MdManufacturers::find($request->input('id'));
            $manufacturer->name = $request->input('name');
            $manufacturer->user_id = Auth::user()->id;
            $manufacturer->url = $request->input('url');
            $manufacturer->support_url = $request->input('support_url');
            $manufacturer->support_phone = $request->input('support_phone');
            $manufacturer->support_email = $request->input('support_email');
            $manufacturer->save();
    
            if ($request->hasFile('manufacturer_img')) {
                $imgPath = FileUploadController::uploadDeviceImage($request->file('manufacturer_img'));
                $manufacturer->image = $imgPath;
                $manufacturer->save();
            }

            return back()->with('success', 'Cập nhật thành công!');
        } catch (Exception $e) {
            return back()->withErrors(['Cập nhật thất bại!']);
        }
    }

    public function delete(Request $request)
    {
        try{
            $manufacturer = MdManufacturers::find($request->input('id'));
            if ( $manufacturer->deleted_at != null ){
                $manufacturer->deleted_at = null;
            } else {
                $manufacturer->deleted_at = date("Y-m-d H:i:s");
            }
            $manufacturer->user_id = Auth::user()->id;
            $manufacturer->save();
            return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
        }catch(Exception $e){
            return response()->json(['message' => 'Cập nhật thất bại, vui lòng thử lại.'], 422)->header('Content-Type', 'application/json');
        }
    }
}
