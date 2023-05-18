<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MdRequest extends Model
{
    use HasFactory;

    protected $table = 'requests';
    protected $primaryKey = 'request_id';
    protected $appends = ['main_person'];
    public $incrementing = false;
    public $timestamps = true;

    public function getMainPersonAttribute()
    {
        if ($this->handler_id == Auth::user()->username ) {
            return true;
        } else {
            return false;
        }
    }

    public function department()
    {
        return $this->belongsTo(MdDepartment::class, 'department_id', 'department_id');
    }
    public function requester(){
        return $this->belongsTo(User::class, 'requester_id', 'username');
    }

    public function assign(){
        return $this->belongsTo(User::class, 'assign_person', 'username');
    }

    public function handler(){
        return $this->belongsTo(User::class, 'handler_id', 'username');
    }

    public function files(){
        return $this->hasMany(MdFileUpload::class, 'request_id');
    }

    public function type(){
        return $this->belongsTo(MdRequestType::class, 'request_type', 'request_type');
    }

    public function src_tp(){
        return $this->belongsTo(MdResourceType::class, 'resource', 'resource_type');
    }

    public function fn_src_tp(){
        return $this->belongsTo(MdResourceType::class, 'final_resource', 'resource_type');
    }

    public function sub_handler(){
        return $this->hasMany(MdRequestSubPerson::class, 'request_id');
    }
}
