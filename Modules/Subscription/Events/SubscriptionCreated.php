<?php

namespace Modules\Subscription\Events;

use Illuminate\Queue\SerializesModels;

class SubscriptionCreated
{
    use SerializesModels;

    public string $order_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $order_id)
    {
        $this->order_id = $order_id;
    }

}
