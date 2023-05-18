<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdDepartment extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $primaryKey = 'department_id';
    public $incrementing = false;
    public $timestamps = false;

    public function users(){
        return $this->hasMany(User::class, 'department_id', 'department_id');
    }
}
