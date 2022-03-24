<?php

namespace Modules\Payment\Services;

use App\Utility;
use Modules\Subscription\Entities\BillingModel;

class PaymentService
{
    public function get(string $id): array
    {
        $response = BillingModel::find($id);
        $found = $response !== null && $response->is_paid;
        return Utility::array_filter([
            'found' => $found,
            'details' => $found == true ? $response->details() : null
        ]);
    }

}
