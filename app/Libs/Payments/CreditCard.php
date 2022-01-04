<?php

namespace App\Libs\Payments;

class CreditCard implements Payments
{
    public function pay()
    {
        return 'CreditCard';
    }
}