<?php

namespace App\Services;

use Exception;
use App\Libs\Payments\Payments;

class CheckoutService
{
    protected $payments;

    public function __construct(Payments $payments)
    {
        $this->payments = $payments;
    }

    public function show()
    {
        return $this->payments->pay();
    }
}
