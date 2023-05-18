<?php

namespace App\Http\Controllers;

use App\Models\MdAssetLogs;
use App\Models\MdAssets;
use App\Models\MdCategories;
use App\Models\MdDepartment;
use App\Models\MdDepreciation;
use App\Models\MdLicenses;
use App\Models\MdLicenseSeats;
use App\Models\MdMaintenances;
use App\Models\MdManufacturers;
use App\Models\MdModels;
use App\Models\MdRequestables;
use App\Models\MdStatus;
use App\Models\MdSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceManagePageController extends Controller
{
    public function getAssets()
    {
        return view('pages.device.assets', [
            'status' => MdStatus::orderBy('sequence')->get(),
            'department' => MdDepartment::orderBy('department_id')->get(),
        ]);
    }

    public function getAssetById($id)
    {
        if (MdAssets::find($id)){
            return view('pages.device.assetEdit', [
                'asset' => MdAssets::find($id),
                'status' => MdStatus::whereNull('deleted_at')->orderBy('sequence')->get(),
                'categories' => MdCategories::with('models')->where('category_type', 'hardware')->whereNull('deleted_at')->get(),
                'suppliers' => MdSupplier::whereNull('deleted_at')->get(),
                'departments' => MdDepartment::get(),
            ]);
        } else {
            return abort(404);
        }
    }

    public function addAssets()
    {
        return view('pages.device.assetCreate', [
            'status' => MdStatus::whereNull('deleted_at')->orderBy('sequence')->get(),
            'categories' => MdCategories::with('models')->where('category_type', 'hardware')->whereNull('deleted_at')->get(),
            'suppliers' => MdSupplier::whereNull('deleted_at')->get(),
            'departments' => MdDepartment::get(),
        ]);
    }

    public function assetsDeploy($id)
    {
        $asset = MdAssets::find($id);
        if ($asset){
            return view('pages.device.assetDeploy', [
                'asset' => $asset,
                'status' => MdStatus::whereNull('deleted_at')->orderBy('sequence')->get(),
                'categories' => MdCategories::with('models')->where('category_type', 'hardware')->whereNull('deleted_at')->get(),
                'suppliers' => MdSupplier::whereNull('deleted_at')->get(),
                'departments' => MdDepartment::with('users')->get(),
            ]);
        } else {
            return abort(404);
        }
    }

    public function assetsClone($id)
    {
        $asset = MdAssets::find($id);
        if ($asset){
            return view('pages.device.assetClone', [
                'asset' => $asset,
                'status' => MdStatus::whereNull('deleted_at')->orderBy('sequence')->get(),
                'categories' => MdCategories::with('models')->where('category_type', 'hardware')->whereNull('deleted_at')->get(),
                'suppliers' => MdSupplier::whereNull('deleted_at')->get(),
                'departments' => MdDepartment::with('users')->get(),
            ]);
        } else {
            return abort(404);
        }
    }

    public function assetLogs($id)
    {
        $asset = MdAssets::find($id);
        $logs = MdAssetLogs::with('asset','user.department','assigned.department')->where('asset_id', $id)->orderBy('updated_at')->get();
        if ($asset){
            return view('pages.device.assetLogs', [
                'asset' => $asset,
                'logs' => $logs
            ]);
        } else {
            return abort(404);
        }
    }

    public function getCategories()
    {
        return view('pages.device.categories');
    }

    public function getCategoryById($id)
    {
        if (MdCategories::find($id)){
            return view('pages.device.categoriesEdit', [
                'category' => MdCategories::find($id),
                'category_type' => DB::table('categories')->distinct('category_type')->groupBy('category_type')->get('category_type'),
            ]);
        } else {
            return abort(404);
        }
    }

    public function addCategories()
    {
        return view('pages.device.categoriesCreate', [
            'category_type' => DB::table('categories')->distinct('category_type')->groupBy('category_type')->get('category_type'),
        ]);
    }

    public function getManufacturers()
    {
        return view('pages.device.manufacturers');
    }

    public function getManufacturerById($id)
    {
        if (MdManufacturers::find($id)) {
            return view('pages.device.manufacturerEdit', [
                'manufacturer' => MdManufacturers::find($id),
            ]);
        } else {
            return abort(404);
        }
    }

    public function addManufacturer()
    {
        return view('pages.device.manufacturerCreate', []);
    }

    public function getModels()
    {
        return view('pages.device.models');
    }

    public function getModelById($id)
    {
        if (MdModels::find($id)) {
            return view('pages.device.modelEdit', [
                'model' => MdModels::find($id),
                'category' => MdCategories::whereNull('deleted_at')->get(),
                'manufacturer' => MdManufacturers::whereNull('deleted_at')->get(),
                'depreciation' => MdDepreciation::get(),
            ]);
        }else{
            return abort(404);
        }
    }

    public function addModel()
    {
        return view('pages.device.modelCreate', [
            'category' => MdCategories::whereNull('deleted_at')->where('category_type', 'Hardware')->get(),
            'manufacturer' => MdManufacturers::whereNull('deleted_at')->get(),
            'depreciation' => MdDepreciation::get(),
        ]);
    }

    public function getSuppliers()
    {
        return view('pages.device.suppliers');
    }

    public function getSupplierById($id)
    {
        if (MdSupplier::find($id)){
            return view('pages.device.supplierEdit', [
                'supplier' => MdSupplier::find($id),
            ]);
        } else {
            return abort(404);
        }
    }

    public function addSupplier()
    {
        return view('pages.device.supplierCreate');
    }

    public function getMaintenances()
    {
        return view('pages.device.maintenances');
    }

    public function getMaintenanceById($id)
    {
        if (MdAssets::where('status_id', 5)->find($id)){
            return view('pages.device.maintenanceUpdate', [
                'asset' => MdAssets::find($id),
                'maintenance' => MdMaintenances::with('asset', 'supplier', 'uploads')->where('asset_id', $id)->whereIn('status', [1,2])->first(),
                'suppliers' => MdSupplier::get(),
            ]);
        } else {
            return redirect()->route('maintenances');
        }
    }

    public function maintenanceLogs($id)
    {
        $asset = MdAssets::find($id);
        $logs = MdMaintenances::with('asset','supplier','user')->where('asset_id', $id)->orderBy('updated_at')->get();
        if ($asset){
            return view('pages.device.maintenanceLogs', [
                'asset' => $asset,
                'logs' => $logs
            ]);
        } else {
            return abort(404);
        }
    }

    function maintenanceLogDetail($id)
    {
        $maintenance = MdMaintenances::find($id);
        $asset = MdAssets::find($maintenance->asset_id);
        if ($asset){
            return view('pages.device.maintenanceLogDetail', [
                'asset' => $asset,
                'maintenance' => $maintenance
            ]);
        } else {
            return abort(404);
        }
    }

    public function getRequestables()
    {
        return view('pages.device.requestables',[]);
    }

    public function getRequestableById($assetId)
    {
        $asset = MdAssets::find($assetId);
        if ($asset){
            return view('pages.device.requestableCreate', [
                'asset' => $asset,
            ]);
        } else {
            return abort(404);
        }
    }

    public function getCheckouts()
    {
        return view('pages.device.checkouts');
    }


    public function getCheckoutById($id)
    {
        $checkouts = MdRequestables::find($id);
        if ($checkouts) {
            return view('pages.device.assetCheckout', [
                'asset' => MdAssets::find($checkouts->asset_id),
                'checkout' => $checkouts,
                'departments' => MdDepartment::get(),
            ]);
        } else {
            return redirect()->route('checkouts');
        }
    }

    public function getLicenses()
    {
        return view('pages.device.licenses');
    }

    public function addLicenses()
    {
        return view('pages.device.licenseCreate', [
            'category' => MdCategories::where('category_type', 'Software')->whereNull('deleted_at')->get(),
            'suppliers' => MdSupplier::whereNull('deleted_at')->get(),
            'manufacturers' => MdManufacturers::whereNull('deleted_at')->get(),
        ]);
    }

    public function getLicensesById($id)
    {
        $license = MdLicenses::find($id);
        if ($license){
            return view('pages.device.licenseUpdate', [
                'license' => $license,
                'category' => MdCategories::where('category_type', 'Software')->whereNull('deleted_at')->get(),
                'suppliers' => MdSupplier::whereNull('deleted_at')->get(),
                'manufacturers' => MdManufacturers::whereNull('deleted_at')->get(),
            ]);
        } else {
            return abort(404);
        }
        
    }

    public function LicensesDeploy($id)
    {
        $license = MdLicenses::whereNull('deleted_at')->find($id);
        
        if ($license){
            $models = MdModels::with('assets')
                ->whereNull('deleted_at')
                ->whereHas('assets', function($q) {
                    return $q->whereNull('deleted_at');
                })
                ->get();

            return view('pages.device.licenseDeploy', [
                'license' => $license,
                'departments' => MdDepartment::with('users')->get(),
                'models' => $models,
            ]);
        } else {
            return redirect()->route('licenses');
        }
    }
}
