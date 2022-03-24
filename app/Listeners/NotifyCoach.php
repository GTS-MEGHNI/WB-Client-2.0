<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Notification;
use Modules\Dashboard\Events\BodyProgressSubmitted;
use Modules\Dashboard\Notifications\SendProgressNotification;
use Modules\Payment\Events\PaymentSubmitted;
use Modules\Payment\Notifications\SendPaymentNotification;
use Modules\Subscription\Entities\CoachModel;
use Modules\Subscription\Events\SubscriptionCancelled;
use Modules\Subscription\Events\SubscriptionCreated;
use Modules\Subscription\Notifications\NewSubscriptionNotification;
use Modules\Subscription\Notifications\SubscriptionCancelledNotification;

class NotifyCoach
{
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
     * @param object $event
     * @return void
     */
    public function handle(mixed $event)
    {
        if ($event instanceof PaymentSubmitted) {
            Notification::send(CoachModel::all(),
                new SendPaymentNotification($event->order_id, $event->payment_method));
        } elseif ($event instanceof BodyProgressSubmitted) {
            Notification::send(CoachModel::all(), new SendProgressNotification());
        } elseif ($event instanceof SubscriptionCreated) {
            Notification::send(CoachModel::all(), new NewSubscriptionNotification($event->order_id));
        } elseif ($event instanceof SubscriptionCancelled) {
            Notification::send(CoachModel::all(), new SubscriptionCancelledNotification($event->order_id));
        }
    }
}
