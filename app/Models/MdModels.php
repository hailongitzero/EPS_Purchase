<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdModels extends Model
{
    use HasFactory;
    protected $table = 'models';

    public function manufacturer()
    {
        return $this->belongsTo(MdManufacturers::class, 'manufacturer_id', 'id');
    }

    public function depreciation()
    {
        return $this->belongsTo(MdDepreciation::class, 'depreciation_id', 'id');
    }

    public function categories()
    {
        return $this->belongsTo(MdCategories::class, 'category_id', 'id');
    }

    public function assets()
    {
        return $this->hasMany(MdAssets::class, 'model_id', 'id');
    }
}
