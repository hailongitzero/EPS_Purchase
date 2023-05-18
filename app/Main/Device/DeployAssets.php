<?php

namespace App\Main\Device;

use App\Models\MdAssets;
use App\Models\User;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeployAssets {
    public static function cloneDeploy($masterAsset, $params)
    {
        try {
            // $cost = round($masterAsset->purchase_cost / $masterAsset->quantity, 0);
            DB::beginTransaction();
            $newAsset = $masterAsset->replicate()->fill([
                'asset_tag' => $params['asset_tag'],
                'serial' => $params['serial'],
                'user_id' => Auth::user()->id,
                // 'purchase_cost' => $cost,
                'quantity' => 1,
                'assigned_to' => $params['assigned_id'],
                'department_id' => User::find($params['assigned_id'])->department_id,
                'status_id' => $params['status_id']
            ]);
            $newAsset->save();

            // $masterAsset->purchase_cost = $masterAsset->purchase_cost - $cost;
            $masterAsset->quantity = $masterAsset->quantity - 1;
            $masterAsset->user_id = Auth::user()->id;
            $masterAsset->save();
            DB::commit();

            return $newAsset;
        } catch (Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public static function deploy($asset, $params)
    {
        try {
            DB::beginTransaction();
            $asset->asset_tag = $params['asset_tag'];
            $asset->serial = $params['serial'];
            $asset->user_id = Auth::user()->id;
            $asset->assigned_to = $params['assigned_id'];
            $asset->department_id = User::find($params['assigned_id'])->department_id;
            $asset->status_id = $params['status_id'];
            $asset->save();
            DB::commit();

            return $asset;
        } catch (Exception $e) {
            DB::rollBack();
            return null;
        }
    }
}