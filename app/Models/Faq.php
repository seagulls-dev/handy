<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Faq extends Model
{

    protected $table = 'faq';

    protected $fillable = ['title', 'short_description', 'description'];
}
