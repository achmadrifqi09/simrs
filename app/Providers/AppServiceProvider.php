<?php

namespace App\Providers;

use App\Http\Middleware\GuzzleLoggingMiddleware;
use App\Repositories\Implements\BPJSQueueResponseRepository;
use App\Repositories\Implements\DoctorRepository;
use App\Repositories\Implements\DoctorScheduleRepository;
use App\Repositories\Implements\PatientRepository;
use App\Repositories\Implements\PolyclinicRepository;
use App\Repositories\Implements\QueueRepository;
use App\Repositories\Interfaces\BPJSQueueResponseInterface;
use App\Repositories\Interfaces\DoctorInterface;
use App\Repositories\Interfaces\DoctorScheduleInterface;
use App\Repositories\Interfaces\PatientInterface;
use App\Repositories\Interfaces\PolyclinicInterface;
use App\Services\BPJSQueueResponseService;
use App\Services\DoctorScheduleService;
use App\Services\DoctorService;
use App\Services\PatientService;
use App\Services\PolyclinicService;
use App\Services\QueueService;
use Carbon\Carbon;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Ramsey\Collection\QueueInterface;
use Filament\Support\Colors\Color;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PolyclinicInterface::class, PolyclinicRepository::class);
        $this->app->bind(PolyclinicService::class, function ($app) {
            return new PolyclinicService($app->make(PolyclinicRepository::class));
        });

        $this->app->bind(DoctorScheduleInterface::class, DoctorScheduleRepository::class);
        $this->app->bind(DoctorScheduleService::class, function ($app) {
            return new DoctorScheduleService($app->make(DoctorScheduleRepository::class));
        });

        $this->app->bind(PatientInterface::class, PatientRepository::class);
        $this->app->bind(PatientService::class, function ($app) {
            return new PatientService($app->make(PatientRepository::class));
        });

        $this->app->bind(QueueInterface::class, QueueRepository::class);
        $this->app->bind(QueueService::class, function ($app) {
            return new QueueService(
                $app->make(QueueRepository::class),
                $app->make(PatientService::class),
                $app->make(DoctorScheduleService::class),
                $app->make(BPJSQueueResponseService::class)
            );
        });

        $this->app->bind(BPJSQueueResponseInterface::class, BPJSQueueResponseRepository::class);

        $this->app->bind(DoctorInterface::class, DoctorRepository::class);
        $this->app->bind(DoctorService::class, function ($app) {
            return new DoctorService(
                $app->make(DoctorRepository::class),
                $app->make(DoctorScheduleService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentColor::register([
            'primary' => Color::Red
        ]);
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        Schema::defaultStringLength(191);

        view()->composer('layouts.queue', function ($view) {
            $view->with('data', [
                'currentDate' => Carbon::now()->format('d-m-Y'),
                'currentTime' => Carbon::now()->format('H:i:s')
            ]);
        });
    }
}
