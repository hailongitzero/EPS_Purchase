<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdRequestSubPerson extends Model
{
    use HasFactory;

    protected $table = 'sub_person';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['request_id', 'username', 'name', 'content'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}
