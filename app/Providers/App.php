<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Domains\Core\Traits\Factory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class App extends ServiceProvider
{
    use Factory;

    /**
     * @return void
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('ROUTE', Route::currentRouteName());
        });

        View::composer('*', function ($view) {
            $view->with('AUTH', Auth::user());
        });

        $this->configuration();
        $this->language();
    }

    /**
     * @return void
     */
    protected function configuration(): void
    {
        $this->factory('Configuration')->action()->appBind();
    }

    /**
     * @return void
     */
    protected function language(): void
    {
        $this->factory('Language')->action()->set();
    }
}
