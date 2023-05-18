<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdAssetLogs extends Model
{
    use HasFactory;

    protected $table = 'asset_logs';

    public function asset()
    {
        return $this->belongsTo(MdAssets::class, 'asset_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'checkedout_to', 'id');
    }
}
