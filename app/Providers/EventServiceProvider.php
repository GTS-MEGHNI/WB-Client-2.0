<?php

namespace App\Providers;

use App\Listeners\NotifyCoach;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Authentication\Events\AccountRegistered;
use Modules\Authentication\Events\UserLogged;
use Modules\Dashboard\Events\BodyProgressSubmitted;
use Modules\DietPlan\Events\FoodConsumed;
use Modules\DietPlan\Events\FoodNotConsumed;
use Modules\DietPlan\Listeners\UpdateDietProgress;
use Modules\Notification\Listeners\RecordNotification;
use Modules\Payment\Events\PaymentSubmitted;
use Modules\Payment\Listeners\UpdateOrderStatus;
use Modules\Subscription\Events\SubscriptionCancelled;
use Modules\Subscription\Events\SubscriptionCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PaymentSubmitted::class => [
            UpdateOrderStatus::class,
            RecordNotification::class,
            NotifyCoach::class
        ],
        AccountRegistered::class => [
            RecordNotification::class
        ],
        UserLogged::class => [
            //RecordNotification::class
        ],
        BodyProgressSubmitted::class => [
            RecordNotification::class,
            NotifyCoach::class
        ],
        SubscriptionCreated::class => [
            RecordNotification::class,
            NotifyCoach::class
        ],
        SubscriptionCancelled::class => [
            RecordNotification::class,
            NotifyCoach::class
        ],
        FoodConsumed::class => [
            UpdateDietProgress::class
        ],
        FoodNotConsumed::class => [
            UpdateDietProgress::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
