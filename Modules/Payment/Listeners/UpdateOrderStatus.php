<?php

namespace Modules\Payment\Listeners;

use App\Dictionary;
use Modules\Payment\Events\PaymentSubmitted;
use Modules\Subscription\Traits\Order;
use Throwable;

class UpdateOrderStatus
{

    use Order;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param PaymentSubmitted $event
     * @return void
     * @throws Throwable
     */
    public function handle(PaymentSubmitted $event)
    {
        $order = $this->getUserLatestOrder();
        $order->status = Dictionary::PAYMENT_SUBMITTED;
        $order->save();
    }
}
