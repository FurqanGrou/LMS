<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers'; // students
    protected $namespace_teachers = 'App\Http\Controllers\Teachers'; // teachers
    protected $namespace_dashboard = 'App\Http\Controllers\Dashboard'; // admins
    protected $namespace_api = 'App\Http\Controllers\API';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/dashboard_students/home'; // students
    public const HOME_TEACHERS = '/dashboard-teachers/home'; // teachers
    public const HOME_DASHBOARD = '/dashboard-admins'; // admins

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes(); // students

        $this->mapWebTeachersRoutes(); // teachers

        $this->mapWebDashboardRoutes(); // admins

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebTeachersRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace_teachers)
            ->group(base_path('routes/teachers/web.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebDashboardRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace_dashboard)
            ->group(base_path('routes/dashboard/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace_api)
             ->group(base_path('routes/api.php'));
    }
}
