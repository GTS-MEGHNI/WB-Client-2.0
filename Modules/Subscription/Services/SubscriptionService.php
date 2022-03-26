<?php

namespace Modules\Subscription\Services;

use App\Dictionary;
use App\Utility;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Subscription\Traits\Order;
use Throwable;

class SubscriptionService
{
    use Order;

    /**
     * @return array
     * @throws Throwable
     * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
     */
    public function get(): array
    {
        $order = $this->getUserLatestOrder();
        $found = $order != null &&
            !in_array($order->status, [Dictionary::DELETED_ORDER, Dictionary::CANCELED_ORDER]);
        return [
            'details' => $found == true ? $order->toArray() : null,
            'found' => $found
        ];
    }

    /**
     * @throws Throwable
     */
    public function cancel()
    {
        $order = $this->getUserLatestOrder();
        $order->status = Dictionary::CANCELED_ORDER;
        $order->save();
    }

    /**
     * @throws Throwable
     */
    public function delete()
    {
        $order = request()->get('order');
        $order->status = Dictionary::DELETED_ORDER;
        $order->save();
    }
}
