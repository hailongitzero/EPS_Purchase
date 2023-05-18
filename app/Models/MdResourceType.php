<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdResourceType extends Model
{
    use HasFactory;

    protected $table = 'resource';
    protected $primaryKey = 'resource_type';
    public $incrementing = false;
    public $timestamps = false;
}
