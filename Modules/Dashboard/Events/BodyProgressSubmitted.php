<?php

namespace Modules\Dashboard\Events;

use Illuminate\Queue\SerializesModels;

class BodyProgressSubmitted
{
    use SerializesModels;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

}
