<?php

namespace App\Providers;

use App\Models\Decision;
use App\Models\Document;
use App\Policies\DecisionPolicy;
use App\Policies\DocumentPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrapFive();

        Gate::policy(Decision::class, DecisionPolicy::class);
        Gate::policy(Document::class, DocumentPolicy::class);

        Relation::morphMap([
            'decision' => Decision::class,
            'document' => Document::class,
        ]);
    }
}
