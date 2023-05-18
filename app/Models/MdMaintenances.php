<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdMaintenances extends Model
{
    use HasFactory;

    protected $table = 'asset_maintenances';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function asset()
    {
        return $this->belongsTo(MdAssets::class, 'asset_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(MdSupplier::class, 'supplier_id', 'id');
    }

    public function uploads(){
        return $this->hasMany(MdAssetUpload::class, 'maintenance_id', 'id');
    }
}
