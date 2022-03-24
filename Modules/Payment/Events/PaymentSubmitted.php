<?php

namespace Modules\Payment\Events;

use Illuminate\Queue\SerializesModels;

class PaymentSubmitted
{
    use SerializesModels;

    public string $payment_method;
    public string $order_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $payment_method, string $order_id)
    {
        $this->payment_method = $payment_method;
        $this->order_id = $order_id;
    }

}
