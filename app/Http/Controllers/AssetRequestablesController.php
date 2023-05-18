<?php

namespace App\Http\Controllers;

use App\Main\Device\AssetLogs;
use App\Main\Device\DeployAssets;
use App\Main\Device\Requestable;
use App\Main\FetchAssetRequestables;
use App\Models\MdAssets;
use App\Models\MdRequestables;
use Illuminate\Http\Request;

class AssetRequestablesController extends Controller
{
    public function add(Request $request)
    {
        $params = $request->all();
        if (!Requestable::isExist($params)){
            $checkout = Requestable::request($params);
            if ($checkout){
                return back()->with('success', 'Yêu cầu thành công!');
            } else {
                return back()->withInput()->withErrors(['error', 'Yêu cầu thất bại!']);
            }
        } else {
            return back()->withInput()->withErrors(['error', 'Đã yêu cầu, không yêu cầu lại!']);
        }
    }

    public function delete(Request $request)
    {
        $checkout = MdRequestables::find($request->input('id'));
        $checkout->delete();
        return response()->json(['message' => 'Huỷ thành công.'], 200)->header('Content-Type', 'application/json');
    }

    public function checkoutList(Request $request)
    {
        $params = $request->all();
        $params["inprogress"] = true;
        $return = app(FetchAssetRequestables::class)->execute($params);
        
        return response($return, 200)->header('Content-Type', 'application/json');
    }

    public function checkoutAsset(Request $request)
    {
        $params = $request->all();
        $checkout = MdRequestables::where('id', $request->input('id'))->whereNull('accepted_at')->whereNull('denied_at')->first();
        if ($checkout) {
            $params["assigned_id"] = $checkout->user_id;
            $params["asset_id"] = $checkout->asset_id;
            $params["status_id"] = 7;
            $asset = MdAssets::where('id', $checkout->asset_id)->where('status_id', 2)->where('quantity', '>', 0)->first();
            if ($request->input('status') == 'A'){
                $acceptCheckout = Requestable::accept($checkout, $params);
                if ($acceptCheckout && $asset){
                    if ($asset->quantity > 1){
                        $newAsset = DeployAssets::cloneDeploy($asset, $params);
                        $log = AssetLogs::logs($newAsset);
                    } else if ($asset->quantity = 1){
                        $newAsset = DeployAssets::deploy($asset, $params);
                        $log = AssetLogs::logs($newAsset);
                    }
                }
                return back()->with('success', 'Đã chấp nhận yêu cầu.');
            } else {
                $deniedCheckout = Requestable::denied($checkout, $request->all());
                if ($deniedCheckout){
                    return back()->with('success', 'Đã từ chối yêu cầu.');
                } else {
                    return back()->withInput()->withErrors(['error', 'Cập nhật thất bại vui lòng tải lại trang.']);
                }
            }
        } else {
            return redirect()->route('checkouts');
        }

    }
}
