<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MdAssets extends Model
{
    use HasFactory;

    protected $table = 'assets';

    protected $fillable = ['asset_tag', 'serial', 'user_id', 'assigned_to', 'quantity', 'status_id', 'department_id'];

    public function all_model()
    {
        return $this->belongsTo(MdModels::class, 'model_id', 'id');
    }

    public function model()
    {
        return $this->all_model()->whereNull('deleted_at');
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function creater()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(MdStatus::class, 'status_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(MdSupplier::class, 'supplier_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(MdDepartment::class, 'department_id', 'department_id');
    }

    public function uploads(){
        return $this->hasMany(MdAssetUpload::class, 'asset_id', 'id');
    }

    public function logs(){
        return $this->hasMany(MdAssetLogs::class, 'asset_id', 'id');
    }

    public function maintenances(){
        return $this->hasMany(MdMaintenances::class, 'asset_id', 'id');
    }

    public function has_maintenance(){
        return $this->maintenances()->where('status', '!=', 3);
    }

    public function requested()
    {
        return $this->hasMany(MdRequestables::class, 'asset_id', 'id');
    }

    public function user_requested()
    {
        return $this->requested()->where('user_id', Auth::user()->id)->whereNull('accepted_at')->whereNull('denied_at')->whereNull('deleted_at');
    }
}
