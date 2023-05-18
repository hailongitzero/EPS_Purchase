<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Main\FetchSuppliers;
use App\Models\MdSupplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function getSuppliers(Request $request)
    {
        $params = $request->all();
        $return = app(FetchSuppliers::class)->execute($params);
        
        return response($return, 200)->header('Content-Type', 'application/json');
    }

    public function insert(SupplierRequest $request)
    {
        $validated = $request->validated();
        try{
            $supplier = new MdSupplier();
            $supplier->name = $request->input('name');
            $supplier->user_id = Auth::user()->id;
            $supplier->address = $request->input('address');
            $supplier->address2 = $request->input('address2');
            $supplier->province = $request->input('province');
            $supplier->country = $request->input('country');
            $supplier->phone = $request->input('phone');
            $supplier->fax = $request->input('fax') == "on" ? 1 : 0;
            $supplier->email = $request->input('email');
            $supplier->contact = $request->input('contact');
            $supplier->notes = $request->input('notes');
            $supplier->zip = $request->input('zip');
            $supplier->url = $request->input('url');
            $supplier->save();
    
            if ($request->hasFile('supplier_img')) {
                $imgPath = FileUploadController::uploadDeviceImage($request->file('supplier_img'));
                $supplier->image = $imgPath;
                $supplier->save();
            }

            return back()->with('success', 'Tạo mới thành công!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error', 'Tạo mới thất bại!']);
        }

    }

    public function update(SupplierRequest $request)
    {
        $validated = $request->validated();
        try{
            $supplier = MdSupplier::find($request->input('id'));
            $supplier->name = $request->input('name');
            $supplier->user_id = Auth::user()->id;
            $supplier->address = $request->input('address');
            $supplier->address2 = $request->input('address2');
            $supplier->province = $request->input('province');
            $supplier->country = $request->input('country');
            $supplier->phone = $request->input('phone');
            $supplier->fax = $request->input('fax') == "on" ? 1 : 0;
            $supplier->email = $request->input('email');
            $supplier->contact = $request->input('contact');
            $supplier->notes = $request->input('notes');
            $supplier->zip = $request->input('zip');
            $supplier->url = $request->input('url');
            $supplier->save();
    
            if ($request->hasFile('supplier_img')) {
                $imgPath = FileUploadController::uploadDeviceImage($request->file('supplier_img'));
                $supplier->image = $imgPath;
                $supplier->save();
            }

            return back()->with('success', 'Cập nhật thành công!');
        } catch (Exception $e) {
            return back()->withErrors(['Cập nhật thất bại!']);
        }
    }

    public function delete(Request $request)
    {
        try{
            $supplier = MdSupplier::find($request->input('id'));
            if ( $supplier->deleted_at != null ){
                $supplier->deleted_at = null;
            } else {
                $supplier->deleted_at = date("Y-m-d H:i:s");
            }
            $supplier->user_id = Auth::user()->id;
            $supplier->save();
            return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
        }catch(Exception $e){
            return response()->json(['message' => 'Cập nhật thất bại, vui lòng thử lại.'], 422)->header('Content-Type', 'application/json');
        }
    }
}
