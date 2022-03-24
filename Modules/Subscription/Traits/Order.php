<?php

namespace Modules\Subscription\Traits;

use App\Exceptions\BillingException;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Dictionary;
use Modules\Plans\Entities\ProgramModel;
use Modules\Subscription\Entities\SubscriptionModel;
use Throwable;

trait Order
{

    private function getGenderCategoryType(array $body): string
    {
        if ($body['age'] <= 13)
            return Dictionary::CHILD;

        return $body['gender'] == 'male' ? Dictionary::MAN : Dictionary::WOMAN;
    }

    /**
     * @throws Throwable
     */
    private function getBillingTotalPrice(): mixed
    {
        $meta = $this->payload['diet'];
        $price = ProgramModel::where(['program' => $meta['program']])
            ?->first()
            ?->models()
            ?->where(['type' => Arr::exists($meta, 'type') ?
                $meta['type'] : null])
            ?->first()
            ?->plans()
            ?->where(['duration' => $meta['plan']])
            ?->first()
            ?->price;
        throw_if($price == null, BillingException::class);
        return $price;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function getExpirationTimeStamp(): Carbon
    {
        $meta = $this->payload['diet'];
        $num_months = (int)filter_var($meta['plan'], FILTER_SANITIZE_NUMBER_INT);
        return Carbon::now()->addMonths($num_months);
    }

    /**
     * @throws Throwable
     */
    private function getUserLatestOrder()
    {
        return SubscriptionModel::where(['user_id' => Utility::getUserId()])
            ->latest()->first();
    }

}
