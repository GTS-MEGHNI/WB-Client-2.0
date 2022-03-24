<?php

namespace Modules\Payment\Services;

use App\Utility;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Payment\Events\PaymentSubmitted;
use Modules\Subscription\Traits\Order;
use Throwable;

class BasicPaymentService
{
    private string $path;
    use Order;

    /**
     * @param array $payload
     * @param string $payment_method
     * @throws Throwable
     */
    public function save(array $payload, string $payment_method)
    {
        $content = base64_decode($payload['data']);
        $this->path = Utility::getUserId() . '/' . Str::random(50) . '/' .
            Str::random(20) . '.' . request()->get('mime');
        Storage::disk('payments')->deleteDirectory(Utility::getUserId());
        Storage::disk('payments')->put($this->path, $content);
        $this->updateBillingData($payment_method);
        event(new PaymentSubmitted($payment_method, $this->getUserLatestOrder()->id));
    }

    /**
     * @throws Throwable
     */
    private function updateBillingData(string $payment_method)
    {
        $order = $this->getUserLatestOrder();
        $billing = $order->billing;
        $billing->payment_method = $payment_method;
        $billing->proof_path = $this->path;
        $billing->file_type = request()->get('mime');
        $billing->is_paid = 1;
        $billing->save();
    }

}
