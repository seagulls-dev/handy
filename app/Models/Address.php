<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

/**
 * @property  string title
 * @property  string address
 * @property  double latitude
 * @property  double longitude
 * @property  integer user_id
 */
class Address extends Model
{
    use SoftDeletes;

    protected $table = 'addresses';

    protected $fillable = ['title', 'address', 'latitude', 'longitude'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User\User', 'user_id');
    }
}
