<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdRequestables extends Model
{
    use HasFactory;

    protected $table = 'requested_assets';

    public function requester()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function asset()
    {
        return $this->belongsTo(MdAssets::class, 'asset_id', 'id');
    }
}
