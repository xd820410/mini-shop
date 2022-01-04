<?php

namespace App\Libs\Payments;

use App\Libs\Payments\Payments;

class Cod implements Payments
{
    public function pay()
    {
        return 'Cod';
    }
}