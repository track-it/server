<?php

namespace Trackit\Providers;

use Illuminate\Support\ServiceProvider;
use Trackit\Contracts\Attachmentable;
use Trackit\Models\Proposal;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app
            ->when(Trackit\Http\Controller\ProposalAttachmentController::class)
            ->needs(Trackit\Contracts\Attachmentable::class)
            ->give(Trackit\Models\Proposal::class);
    }
}
