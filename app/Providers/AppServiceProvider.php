<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Asset;
use App\Models\AssetChild;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $assets, $docs, $alerts;

    public function register()
    {
    }

    public function boot()
    {
        Schema::defaultStringLength(191);
        Carbon::setLocale('en');
        date_default_timezone_set('Asia/Jakarta');
        $now = now()->addDays(30)->format('Y-m-d');

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->assets = Asset::getLastTransaction($now)->where('trn_status', false)->get();
        $this->docs = AssetChild::getLastTransaction($now)->where('trn_status', false)->get();
        $this->alerts = FillAlertsData($this->assets, $this->docs);

        view()->composer(
            'layouts.master',
            fn ($view) => $view->with(['alerts' => $this->alerts])
        );

        Gate::define('superadmin', function (User $user) {
            return $user->is_admin == 1;
        });

        Gate::define('admin', function (User $user) {
            return $user->is_admin == 2;
        });
    }
}
