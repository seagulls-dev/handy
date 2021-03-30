<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use PhpParser\Node\Scalar\String_;

class NewAppointment
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $appointment;


    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string
     * @return void
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }
}
