<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdVisitor extends Model
{
    use HasFactory;

    protected $table = 'visitor';
    public $timestamps = false;
}
