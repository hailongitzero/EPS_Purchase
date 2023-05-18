<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdCategories extends Model
{
    use HasFactory;

    protected $table = 'categories';

    public function all_models(){
        return $this->hasMany(MdModels::class, 'category_id', 'id');
    }

    public function models(){
        return $this->all_models()->whereNull('deleted_at');
    }
}
