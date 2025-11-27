<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use App\Models\Ticket;
use App\Observers\TicketObserver;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Ensure web routes are registered even if RouteServiceProvider is not picked up
        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        $sharedSettings = [
            'site_name' => config('app.name'),
            'site_tagline' => null,
            'contact_email' => null,
            'contact_phone' => null,
            'contact_street' => null,
            'contact_city' => null,
            'contact_state' => null,
            'contact_postal' => null,
            'contact_country' => null,
            'site_logo' => null,
            'site_logo_dark' => null,
            'site_logo_mobile' => null,
            'mobile_logo_width' => '175',
            'mobile_logo_height' => '155',
            'site_favicon' => null,
        ];

        try {
            foreach ($sharedSettings as $key => $default) {
                $sharedSettings[$key] = Setting::getValue($key, $default);
            }

            if (!empty($sharedSettings['site_name'])) {
                config(['app.name' => $sharedSettings['site_name']]);
            }
        } catch (\Throwable $e) {
            // Settings table may not exist during first migration; ignore silently.
        }

        View::share('appSettings', $sharedSettings);

        View::composer('layouts.partials.header', function ($view) {
            $categories = collect();

            try {
                $categories = Category::query()
                    ->active()
                    ->orderBy('name')
                    ->limit(8)
                    ->get(['id', 'name', 'slug']);
            } catch (\Throwable $e) {
                // Tables might not be migrated yet; avoid breaking the layout.
            }

            $cart = Session::get('cart', []);
            $cartCount = collect($cart)->sum('quantity');

            $view->with([
                'headerCategories' => $categories,
                'headerCartCount' => $cartCount,
            ]);
        });

        Ticket::observe(TicketObserver::class);
    }
}
