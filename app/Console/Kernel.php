<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\MarketplaceApiCronJob;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:refresh-api-tokens-command')->everyFifteenMinutes();
        foreach( MarketplaceApiCronJob::all() as $marketplaceApiCronJob ){
            $shop = $marketplaceApiCronJob->shop;
            $marketplace = $marketplaceApiCronJob->marketplace;
            if( $marketplaceApiCronJob->frequency_type == 'Semanal' ){
                $dias = json_decode($marketplaceApiCronJob->weekdays);
                $horas = json_decode($marketplaceApiCronJob->dayhours);
                foreach( $dias as $dia ){
                    foreach( $horas as $hora ){
                        $schedule->call(function() use ($shop, $marketplace, $marketplaceApiCronJob){
                            $resolverClassName = "App\\Http\\ApiResolvers\\$marketplace->connector_class_name";
                            $apiResolver = new $resolverClassName($marketplace, $shop);
                            $apiResolver->{$marketplaceApiCronJob->cron_name}(json_decode($marketplaceApiCronJob->payload, true));
                        })->weeklyOn($dia, $hora);
                    }
                }
            } elseif( $marketplaceApiCronJob->frequency_type == 'Diaria' ){
                $horas = json_decode($marketplaceApiCronJob->dayhours);
                foreach( $horas as $hora ){
                    $schedule->call(function() use ($shop, $marketplace, $marketplaceApiCronJob){
                        $resolverClassName = "App\\Http\\ApiResolvers\\$marketplace->connector_class_name";
                        $apiResolver = new $resolverClassName($marketplace, $shop);
                        $apiResolver->{$marketplaceApiCronJob->cron_name}(json_decode($marketplaceApiCronJob->payload, true));
                    })->dailyAt($hora);
                }
            }
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
