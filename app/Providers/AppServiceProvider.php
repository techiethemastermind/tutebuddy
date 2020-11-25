<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        if (Schema::hasTable('configs')) {
            foreach (Config::all() as $setting) {
                \Illuminate\Support\Facades\Config::set($setting->key, $setting->value);
            }
        }

        /*
         * setLocale for php. Enables ->formatLocalized() with localized values for dates
         */
        setlocale(LC_TIME, config('app.locale_php'));

        /*
         * setLocale to use Carbon source locales. Enables diffForHumans() localized
         */
        Carbon::setLocale(config('app.locale'));
        App::setLocale(config('app.locale'));
        config()->set('invoices.currency', config('app.currency'));

        view()->composer(['auth.*', 'frontend.*', 'backend.*', 'frontend-rtl.*','vendor.invoices.*'], function ($view) {
            $appCurrency = getCurrency(config('app.currency'));
            if (Schema::hasTable('locales')) {
                $locales = \App\Models\Locale::pluck('short_name as locale')->toArray();
            }
            $view->with(compact('locales','appCurrency'));
        });

        view()->composer(['backend.*'], function ($view) {
            $locale_full_name = 'English';
            $locale =  \App\Models\Locale::where('short_name','=',config('app.locale'))->first();
            if($locale){
                $locale_full_name = $locale->name;
            }
            $view->with(compact('locale_full_name'));
        });
    }
}
