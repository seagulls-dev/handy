<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Category extends Model
{

    protected $table = 'categories';

    protected $fillable = ['title', 'image_url', 'secondary_image_url','parent_id'];

    public function subcategories()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }
}
