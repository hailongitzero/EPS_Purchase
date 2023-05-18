<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Main\FetchCategories;
use App\Models\MdCategories;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function getCategory(Request $request)
    {
        $params = $request->all();
        $return = app(FetchCategories::class)->execute($params);
        
        return response($return, 200)->header('Content-Type', 'application/json');
    }

    public function insert(CategoryRequest $request)
    {
        $validated = $request->validated();
        try{
            $category = new MdCategories();
            $category->name = $request->input('name');
            $category->category_type = $request->input('type');
            $category->user_id = Auth::user()->id;
            $category->save();
    
            if ($request->hasFile('category_img')) {
                $imgPath = FileUploadController::uploadDeviceImage($request->file('category_img'));
                $category->image = $imgPath;
                $category->save();
            }

            return back()->with('success', 'Tạo mới thành công!');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error', 'Tạo mới thất bại!']);
        }

    }

    public function update(CategoryRequest $request)
    {
        $validated = $request->validated();
        try{
            $category = MdCategories::find($request->input('id'));
            $category->name = $request->input('name');
            $category->category_type = $request->input('type');
            $category->user_id = Auth::user()->id;
            $category->save();
    
            if ($request->hasFile('category_img')) {
                $imgPath = FileUploadController::uploadDeviceImage($request->file('category_img'));
                $category->image = $imgPath;
                $category->save();
            }

            return back()->with('success', 'Cập nhật thành công!');
        } catch (Exception $e) {
            return back()->withErrors(['Cập nhật thất bại!']);
        }
    }

    public function delete(Request $request)
    {
        try{
            $category = MdCategories::find($request->input('id'));
            if ( $category->deleted_at != null ){
                $category->deleted_at = null;
            } else {
                $category->deleted_at = date("Y-m-d H:i:s");
            }
            $category->user_id = Auth::user()->id;
            $category->save();
            return response()->json(['message' => 'Cập nhật thành công.'], 200)->header('Content-Type', 'application/json');
        }catch(Exception $e){
            return response()->json(['message' => 'Cập nhật thất bại, vui lòng thử lại.'], 422)->header('Content-Type', 'application/json');
        }
    }
}
