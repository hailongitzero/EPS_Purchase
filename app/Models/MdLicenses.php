<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdLicenses extends Model
{
    use HasFactory;

    protected $table = 'licenses';

    protected $fillable = [];
    protected $appends = ['remain'];

    public function getRemainAttribute()
    {
        if ($this->limit_seats == 1) {
            $count = MdLicenseSeats::where('license_id', $this->id)->whereNull('deleted_at')->count();
            return $this->seats - $count;
        } else {
            return 1;
        }
    }

    public function category()
    {
        return $this->belongsTo(MdCategories::class, 'category_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(MdSupplier::class, 'supplier_id', 'id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(MdManufacturers::class, 'manufacturer_id', 'id');
    }

    public function uploads(){
        return $this->hasMany(MdAssetUpload::class, 'license_id', 'id');
    }

    public function license_seats(){
        return $this->hasMany(MdLicenseSeats::class, 'license_id', 'id');
    }
}
