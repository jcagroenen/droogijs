<?php

namespace App\Providers;

use App\Admin\ShippingCalendar;
use App\WooCommerce\DeliveryDate;
use Roots\Acorn\Sage\SageServiceProvider;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->singleton(DeliveryDate::class);
        $this->app->singleton(ShippingCalendar::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->app->make(DeliveryDate::class)->boot();
        $this->app->make(ShippingCalendar::class)->boot();
    }
}
