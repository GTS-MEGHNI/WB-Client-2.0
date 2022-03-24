<?php

namespace Modules\Subscription\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Subscription\Traits\Order;
use Throwable;

class SubscriptionCancelled
{
    use SerializesModels, Order;

    public string $order_id;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct()
    {
        $this->order_id = $this->getUserLatestOrder()->id;
    }

}
