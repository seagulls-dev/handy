<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use PhpParser\Node\Scalar\String_;

class UpdateAppointment
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $appointment;
    public $rescheduled;


    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string
     * @return void
     */
    public function __construct($appointment, $rescheduled)
    {
        $this->appointment = $appointment;
        $this->rescheduled = $rescheduled;
    }
}
