<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use Resend\Resend;
use Illuminate\Support\Facades\Mail;
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
        Mail::extend('resend', function ($config) {
            return new \Illuminate\Mail\Transport\Transport(function ($message) use ($config) {
                $resend = Resend::client(env('RESEND_API_KEY'));
                $resend->emails->send([
                    'from' => config('mail.from.address'),
                    'to' => array_keys($message->getTo()),
                    'subject' => $message->getSubject(),
                    'html' => $message->getBody(),
                ]);
            });
        });
    }
}
