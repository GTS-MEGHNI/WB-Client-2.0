<?php

namespace Modules\Subscription\Services;

use App\Dictionary;
use App\Utility;
use Modules\Subscription\Entities\BillingModel;
use Modules\Subscription\Entities\BillingPriceModel;
use Modules\Subscription\Entities\BodyModel;
use Modules\Subscription\Entities\FitnessModel;
use Modules\Subscription\Entities\HealthModel;
use Modules\Subscription\Entities\SubscriptionModel;
use Modules\Subscription\Entities\PersonalModel;
use Modules\Subscription\Entities\VisionModel;
use Modules\Subscription\Traits\Order;
use Throwable;

class CheckoutService extends SubscriptionService
{
    use Order;

    public array $payload;
    public string $order_id;
    public array $body;
    public mixed $programPriceData;
    public array $meta;

    /**
     * @throws Throwable
     */
    public function record(array $payload)
    {
        $this->payload = $payload;
        $this->programPriceData = $this->getBillingTotalPrice();
        $this->meta()
            ->body()
            ->fitness()
            ->health()
            ->vision()
            ->billing()
            ->personal();
    }

    /**
     * @return CheckoutService
     * @throws Throwable
     */
    private function meta(): CheckoutService
    {
        $this->order_id = (new OrderIDGeneratorService())->generateID();
        $meta = $this->payload['diet'];
        $this->meta = $meta;
        SubscriptionModel::forceCreate([
            'id' => $this->order_id,
            'status' => Dictionary::PENDING_ORDER,
            'user_id' => Utility::getUserId(),
            'diet' => $meta['program'],
            'diet_type' => $meta['program'] == Dictionary::BALANCED_DIET ? $meta['type'] : null,
            'plan' => $meta['plan']
        ]);
        return $this;
    }

    private function body(): CheckoutService
    {
        $body = $this->payload['body'];
        $this->body = $body;
        BodyModel::forceCreate([
            'order_id' => $this->order_id,
            'gender' => $body['gender'],
            'type' => $this->getGenderCategoryType($body),
            'age' => $body['age'],
            'weight' => $body['weight'],
            'height' => $body['height']
        ]);
        return $this;
    }

    private function fitness(): CheckoutService
    {
        $fitness = $this->payload['fitness'];
        FitnessModel::forceCreate([
            'order_id' => $this->order_id,
            'is_sport' => $fitness['isPractisingSport'],
            'sport_type' => $fitness['isPractisingSport'] == true ? $fitness['sportType'] : null,
            'fitness_sport_times' => $fitness['isPractisingSport'] == true ? $fitness['sportPracticeTimes'] : null,
            'is_using_supplement' => $fitness['isUsingSupplements'],
            'had_diet' => $fitness['hasDietExperience'],
            'diet_exp' => $fitness['hasDietExperience'] == true ? $fitness['dietExperience'] : null,
            'has_food_cons' => $fitness['hasFoodConsiderations'],
            'food_cons' => $fitness['hasFoodConsiderations'] == true ? $fitness['foodConsiderations'] : null,
        ]);
        return $this;
    }

    private function health(): CheckoutService
    {
        $health = $this->payload['health'];
        $is_pregnant = $this->body['gender'] == Dictionary::FEMALE ? $health['isPregnant'] : null;
        $breast_feeding = $this->body['gender'] == Dictionary::FEMALE ? $health['isBreastfeeding'] : null;
        HealthModel::forceCreate([
            'order_id' => $this->order_id,
            'has_phy_issues' => $health['hasPhysicalHealthIssues'],
            'phy_issues' => $health['hasPhysicalHealthIssues'] == true ? $health['physicalHealthIssues'] : null,
            'taking_medication' => $health['hasPhysicalHealthIssues'] == true ? $health['isTakingMedications'] : null,
            'medication' => $health['hasPhysicalHealthIssues'] == true ?
                ($health['isTakingMedications'] == true ? $health['medications'] : null) : null,
            'has_mental_issues' => $health['hasMentalHealthIssues'],
            'mental_state' => $health['hasMentalHealthIssues'] == true ?
                $health['mentalHealthState'] : null,
            'is_pregnant' => $is_pregnant,
            'pregnancy_month' => $is_pregnant == true ? $health['pregnancyMonth'] : null,
            'breast_feeding' => $breast_feeding,
            'lactation_month' => $breast_feeding == true ? $health['lactationMonth'] : null
        ]);
        return $this;
    }

    private function vision(): CheckoutService
    {
        $vision = $this->payload['vision'];
        VisionModel::forceCreate([
            'order_id' => $this->order_id,
            'objectives' => $vision['objectives'],
            'will_practise_sport' => $vision['willPractiseSport'],
            'sport_times' => $vision['willPractiseSport'] == true ? $vision['sportPracticeTimes'] : null,
            'weight_goal' => $this->meta['program'] == Dictionary::FAT_LOSS_DIET ||
            $this->meta['program'] == Dictionary::WEIGHT_GAIN_DIET ? $vision['weightGoal'] : null,
            'budget' => $vision['budget'],
            'has_request' => $vision['hasSpecialRequest'],
            'request' => $vision['hasSpecialRequest'] == true ? $vision['specialRequest'] : null
        ]);
        return $this;
    }

    private function personal(): CheckoutService
    {
        $personal = $this->payload['personal'];
        PersonalModel::forceCreate([
            'order_id' => $this->order_id,
            'first_name' => $personal['firstname'],
            'last_name' => $personal['lastname'],
            'country' => $personal['country'],
            'province' => $personal['province']
        ]);
        return $this;
    }

    private function billing(): CheckoutService
    {
        $billing_row = BillingModel::forceCreate([
            'order_id' => $this->order_id,
        ]);
        foreach ($this->programPriceData as $item)
            BillingPriceModel::forceCreate([
                'billing_id' => $billing_row->id,
                'currency' => $item->currency,
                'price' => $item->price
            ]);
        return $this;
    }

}
