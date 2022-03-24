<?php

namespace Modules\Notification\Listeners;

use App\Utility;
use Modules\Authentication\Events\AccountRegistered;
use Modules\Authentication\Events\UserLogged;
use Modules\Dashboard\Events\BodyProgressSubmitted;
use Modules\Notification\Entities\NotificationModel;
use Modules\Payment\Events\PaymentSubmitted;
use Modules\Subscription\Events\SubscriptionCancelled;
use Modules\Subscription\Events\SubscriptionCreated;
use Modules\Subscription\Traits\Order;
use Throwable;

class RecordNotification
{

    use Order;

    private string $link, $content, $type;

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
     * @param mixed $event
     * @return void
     * @throws Throwable;
     */
    public function handle(mixed $event)
    {
        $this->setNotificationFields($event);
        NotificationModel::forceCreate([
            'type' => $this->type,
            'content' => $this->content,
            'link' => $this->link,
            'is_for_admin' => true,
        ]);
    }

    /**
     * @param mixed $event
     * @throws Throwable
     */
    private function setNotificationFields(mixed $event): void
    {
        if ($event instanceof PaymentSubmitted) {
            $this->type = 'payment';
            $this->content = 'Un paiment viens d\'être soumi pour la souscription N° ' . $event->order_id;
            $this->link = '/dashboard/coaching/subscriptions/' . $event->order_id;
        } elseif ($event instanceof AccountRegistered) {
            $this->type = 'user';
            $this->content = 'Un nouveau visiteur vient de s\'inscrire à la plateforme';
            $this->link = '/dashboard/users/' . $event->user->user_id;
        } elseif ($event instanceof UserLogged) {
            $this->type = 'student';
            $this->content = 'L\'étudiant ' . $event->user->first_name . ' vient de se connecter';
            $this->link = '/dashboard/coaching/classroom/students/' . $this->getUserLatestOrder()->id;
        } elseif ($event instanceof BodyProgressSubmitted) {
            $this->type = 'analytics';
            $this->content = 'L\'étudiant N°' . Utility::getUserId() . ' vient de soumettre son progrès';
            $this->link = '/dashboard/coaching/classroom/students/'.$this->getUserLatestOrder()->id.'/progress';
        } elseif ($event instanceof SubscriptionCreated) {
            $this->type = 'subscription';
            $this->content = 'Une nouvelle souscription viens d\'être ajoutée dans le système';
            $this->link = '/dashboard/coaching/subscriptions/'.$event->order_id;
        } elseif ($event instanceof SubscriptionCancelled) {
            $this->type = 'subscription';
            $this->content = 'La souscription N° ' . $event->order_id . ' viens d\'être annulée';
            $this->link = '/dashboard/coaching/subscriptions/'.$event->order_id;
        }
    }
}
