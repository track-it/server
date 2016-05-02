<?php

namespace Trackit\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Trackit\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapRoutes($router);

        //
    }

    /**
     * Define the routes for the application.
     *
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace,
        ], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
