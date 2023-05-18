<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdAssetUpload extends Model
{
    use HasFactory;

    protected $table = 'asset_uploads';

    public function asset()
    {
        return $this->belongsTo(MdAssets::class, 'asset_id', 'id');
    }
}
