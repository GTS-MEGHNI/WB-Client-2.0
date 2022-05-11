<?php

namespace Modules\Payment\Services;

use App\Utility;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Payment\Entities\ReceiptPaymentModel;
use Modules\Payment\Events\PaymentSubmitted;
use Modules\Subscription\Traits\Order;
use Throwable;

class BasicPaymentService
{
    use Order;

    /**
     * @param array $payload
     * @param string $payment_method
     * @throws Throwable
     */
    public function save(array $payload, string $payment_method)
    {
        $content = base64_decode($payload['data']);
        $path = Utility::getUserId() . '/' . Str::random(50) . '/' .
            Str::random(20) . '.' . request()->get('mime');
        Storage::disk('payments')->deleteDirectory(Utility::getUserId());
        Storage::disk('payments')->makeDirectory(Utility::getUserId());
        Storage::disk('payments')->put($path, $content);
        $subscription = Utility::getUserOngoingSubscription();
        ReceiptPaymentModel::forceCreate([
            'id' => uniqid(),
            'user_id' => Utility::getUserId(),
            'subscription_id' => $subscription->id,
            'billing_id' => $subscription->billing->id,
            'file_type' => request()->get('mime'),
            'payment_method' => $payment_method,
            'receipt_path' => $path
        ]);
        event(new PaymentSubmitted($payment_method,
            $subscription->id));
    }


    /*private function updateBillingData(string $payment_method)
    {
        $order = $this->getUserLatestOrder();
        $billing = $order->billing;
        $billing->payment_method = $payment_method;
        $billing->proof_path = $this->path;
        $billing->file_type = request()->get('mime');
        $billing->is_paid = 1;
        $billing->save();
    }*/

}
