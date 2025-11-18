<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Listeners\MergeCartAfterLogin;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            MergeCartAfterLogin::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false; // sin discovery => no hay glob()
    }
}
