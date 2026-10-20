<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\QuoteRequest;
use App\Services\Content\ContentOverrides;
use App\View\Composers\Admin\SidebarComposer;
use App\View\Composers\QuoteFormComposer;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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
        // Admin migrations live in their own folder: database/migrations/admin
        $this->loadMigrationsFrom(database_path('migrations/admin'));

        // Wording edited in Admin > Landing content overrides config/landing.php and config/site.php
        ContentOverrides::apply();

        // Short stable names stored in inquiry_replies.inquiry_type (instead of full class names)
        Relation::morphMap([
            'contact_message' => ContactMessage::class,
            'quote_request'   => QuoteRequest::class,
        ]);

        // @adminAsset('css/base.css')  ->  full URL of a file in public/assets/admin
        Blade::directive('adminAsset', function ($expression) {
            return "<?php echo \\App\\Support\\AdminAsset::url({$expression}); ?>";
        });

        // Data every admin page needs / the quote form needs (so controllers stay small)
        View::composer('admin.*', SidebarComposer::class);
        View::composer('pages.quote', QuoteFormComposer::class);
    }
}
