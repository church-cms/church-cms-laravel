<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->booted(function () {
            $active      = config('settings.active_theme', 'default');
            $defaultPath = resource_path('webbuilder/themes/default');
            $activePath  = resource_path('webbuilder/themes/' . $active);

            $paths = [];
            if ($active !== 'default' && is_dir($activePath)) {
                $paths[] = $activePath;
            }
            $paths[] = $defaultPath;

            view()->addNamespace('theme', $paths);
        });
    }
}
