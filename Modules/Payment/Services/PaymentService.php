<?php

namespace Modules\Payment\Services;

use App\Utility;
use Modules\Payment\Entities\ReceiptPaymentModel;

class PaymentService
{
    public function get(): array
    {
        $payment = ReceiptPaymentModel::find(request()->route('paymentId'));
        return Utility::array_filter([
            'found' => $payment !== null,
            'details' => $payment?->details()
        ]);
    }

}
