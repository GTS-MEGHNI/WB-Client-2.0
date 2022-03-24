<?php

namespace Modules\Dashboard\Services;

use Modules\Dashboard\Entities\QuoteModel;
use Throwable;

class QuoteService
{
    /**
     * @return array
     * @throws Throwable
     */
    public function list(): array
    {
        return QuoteModel::whereIn('category', [null, request()->get('order')->diet])
            ->get()
            ->toArray();
    }

}
