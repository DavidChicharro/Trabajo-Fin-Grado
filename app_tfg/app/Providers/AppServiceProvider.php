<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        Blade::directive('dateTimeFormat', function ($datetime) {
			return "<?php echo date(\"d/m/Y H:i\", strtotime($datetime)); ?>";
		});

		Blade::directive('dateInputFormat', function ($date) {
			return "<?php echo date(\"Y-m-d\", strtotime($date)); ?>";
		});

		Blade::directive('dateFormat', function ($date) {
			return "<?php echo date(\"d/m/Y\", strtotime($date)); ?>";
		});
    }
}
