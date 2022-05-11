<?php

namespace App;

use Modules\Subscription\Entities\SubscriptionModel;
use Modules\Subscription\Traits\Order;
use Throwable;

class Utility
{
    use Order;

    public const EXCLUSIONS = [];

    public static function generatePasscode(): string
    {
        // Send validation code
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 1; $i <= 6; $i++)
            $code = $code . '' . $characters[rand(0, strlen($characters) - 1)];
        return $code;
    }

    public static function generatePaymentID(): string
    {
        $characters = '0123456789';
        $length = 10;
        $identifier = '';
        for ($i = 1; $i <= $length; $i++)
            $identifier = $identifier . '' . $characters[rand(0, strlen($characters) - 1)];
        return $identifier;
    }

    /*public static function stepFormula(int $value): int
    {
        $golden_ratio = (sqrt(5) + 1) / 2;
        return floor((log($value) * pi()) / pow($golden_ratio, 2));
    }*/

    public static function array_filter($array): array
    {
        $response = [];
        foreach ($array as $key => $value)
            if (in_array($key, self::EXCLUSIONS))
                $response[$key] = $value;
            elseif ($value !== null && $value !== [])
                $response[$key] = $value;
        return $response;
    }

    public static function currencyFilter(array $array): ?array
    {
        foreach ($array as $item)
            if ($item['currency'] == Dictionary::DINAR_CURRENCY)
                return $item;
        return null;
    }

    public static function remove_array_shape_tag(array $response): array
    {
        return $response;
    }

    public static function getUserId(): string
    {
        return request()->request->get('user_id');
    }

    /**
     * @return SubscriptionModel
     * @throws Throwable
     */
    public static function getUserOngoingSubscription(): SubscriptionModel
    {
        return (new self())->getUserLatestOrder();
    }

    public static function filterMeasurement(array $measurements): array
    {
        $array = [];
        $response = [];
        foreach ($measurements as $measurement) {
            if (!self::IsDuplicated($array, $measurement['quantity']))
                array_push($response, $measurement);
            array_push($array, $measurement['quantity']);
        }
        return $response;
    }

    private static function IsDuplicated(array $array, array $measurement): bool
    {
        foreach ($array as $item)
            if ($item['unit'] == $measurement['unit'] && $item['amount'] == $measurement['amount'])
                return true;
        return false;
    }


}
