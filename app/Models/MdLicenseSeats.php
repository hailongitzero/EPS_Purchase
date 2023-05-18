<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdLicenseSeats extends Model
{
    use HasFactory;

    protected $table = 'license_seats';

    public function license()
    {
        return $this->belongsTo(MdLicenses::class, 'license_id', 'id');
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function asset(){
        return $this->belongsTo(MdAssets::class, 'asset_id', 'id');
    }
}
