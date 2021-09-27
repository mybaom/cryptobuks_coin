<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class AppServiceProvider extends ServiceProvider
{
    public static $sql_count = 0;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        defined('DECIMAL_SCALE') || define('DECIMAL_SCALE', 8);
        bcscale(DECIMAL_SCALE);

        if(env('APP_DEBUG')){
            DB::listen(function ($query) {
                $sql = $query->sql;
                $bindings = $query->bindings;
                $time = $query->time;
                foreach ($bindings as &$binding){
                    if (is_string($binding)) {
                        $binding = "'$binding'";
                    }
                }
                $sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
                $sql = vsprintf($sql, $bindings);
                Log::debug("SQL>>".$sql, ['bindings' => $bindings, 'time' => $time]);
                self::$sql_count++;
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            return new Client();
        });
        $this->app->singleton('LbxChainServer', function ($app) {
            $api_url = config('app.wallet_api');
            return new Client(['base_uri' => $api_url]);
        });
    }
}
