<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Libs\Payments\Payments;
use App\Libs\Payments\Cod;
use App\Libs\Payments\CreditCard;

class CheckoutServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Payments::class, function($app) {
            $payment = request()->payment;
            if (!empty($payment)) {
                if ($payment == 'Cod') {
                    return new Cod();
                } else if ($payment == 'CreditCard') {
                    return new CreditCard();
                } else {
                    return new Cod();
                }
            }

            return new Cod();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
