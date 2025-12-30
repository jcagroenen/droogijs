<?php

namespace App\Providers;

use App\Admin\ShippingCalendar;
use App\WooCommerce\DeliveryDate;
use App\WooCommerce\FlatPermalinks;
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
        $this->app->singleton(FlatPermalinks::class);
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
        $this->app->make(FlatPermalinks::class)->boot();
    }
}
