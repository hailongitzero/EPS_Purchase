<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdFileUpload extends Model
{
    use HasFactory;
    
    protected $table = 'uploads';
    protected $primaryKey = 'file_id';
    public $timestamps = false;
}
