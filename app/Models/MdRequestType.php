<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdRequestType extends Model
{
    use HasFactory;

    protected $table = 'request_types';
    protected $primaryKey = 'request_type';
    public $incrementing = false;
    public $timestamps = false;
}
