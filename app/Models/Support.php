<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Support extends Model
{

    protected $table = 'supports';

    protected $fillable = ['name', 'email', 'message'];
}
