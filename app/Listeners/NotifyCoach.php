<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Modules\Dashboard\Events\BodyProgressSubmitted;
use Modules\Dashboard\Notifications\SendProgressNotification;
use Modules\Payment\Emails\PaymentSubmittedEmail;
use Modules\Payment\Events\PaymentSubmitted;
use Modules\Payment\Notifications\SendPaymentNotification;
use Modules\Subscription\Emails\SubscriptionCanceledMail;
use Modules\Subscription\Emails\SubscriptionCreatedMail;
use Modules\Subscription\Entities\CoachModel;
use Modules\Subscription\Events\SubscriptionCancelled;
use Modules\Subscription\Events\SubscriptionCreated;

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
            Mail::to(CoachModel::all()->pluck('email')->toArray())
                ->send(new PaymentSubmittedEmail($event->order_id,
                        $event->payment_method)
                );
        } elseif ($event instanceof BodyProgressSubmitted) {
            Notification::send(CoachModel::all(), new SendProgressNotification());
        } elseif ($event instanceof SubscriptionCreated) {
            Mail::to(CoachModel::all()->pluck('email')->toArray())
                ->send(
                    new SubscriptionCreatedMail($event->order_id)
                );
        } elseif ($event instanceof SubscriptionCancelled) {
            Mail::to(CoachModel::all()->pluck('email')->toArray())
                ->send(
                    new SubscriptionCanceledMail($event->order_id)
                );
        }
    }
}
