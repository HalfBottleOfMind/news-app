<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\QueryExecuted;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Facade
        $this->app->bind('apiroute', 'App\Router\ApiRoute');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_DEBUG')) {
            $this->sqlDebug();
        }
    }
    
    /**
     * Logging Queries
     *
     * @return void
     */
    private function sqlDebug()
    {
        DB::listen(function (QueryExecuted $query) {
            File::append(
                storage_path('/logs/query.log'),
                '[' . Carbon::now()->format('d-m-Y H:i:s') . '] '
                . $query->sql . ' [' . implode(', ', $query->bindings) . '], ET: [' . $query->time . ' ms]'
                . PHP_EOL
            );
        });
    }
}
