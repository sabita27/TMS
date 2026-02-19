<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        try {
            if (\Schema::hasTable('settings')) {
                $settings = \App\Models\Setting::all()->pluck('value', 'key');
                
                if (isset($settings['mail_host'])) {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $settings['mail_host'],
                        'mail.mailers.smtp.port' => $settings['mail_port'] ?? 587,
                        'mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?? 'tls',
                        'mail.mailers.smtp.username' => $settings['mail_username'],
                        'mail.mailers.smtp.password' => $settings['mail_password'],
                        'mail.from.address' => $settings['mail_from_address'] ?? config('mail.from.address'),
                        'mail.from.name' => $settings['system_name'] ?? config('mail.from.name'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Avoid crash during migrations or setup
        }
    }
}
