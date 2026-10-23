<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Foundation\Application;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            // 'auth' => [
            //     'user' => $request->user(),
            // ],
            'auth' => [
                'user' => $request->user()
                    ? $request->user()->only('username', 'name', 'role', 'email', 'bio', 'image')
                    : null,
                // Mengambil 10 notifikasi belum dibaca milik user
                // 'unreadNotifications' => $request->user()
                //     ? $request->user()->unreadNotifications()->take(10)->get()
                //     : [],
            ],
            'locale' => app()->getLocale(),
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
                'warning' => fn() => $request->session()->get('warning'),
                'info' => fn() => $request->session()->get('info')
            ],
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'appUrl' => config('app.url'),
            'settings' => fn() => cache()->remember(
                'global_settings',
                now()->addHour(),
                function () {
                    return Setting::pluck('value', 'key');
                }
            ),
        ];
    }
}
