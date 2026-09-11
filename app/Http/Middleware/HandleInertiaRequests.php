<?php

namespace App\Http\Middleware;

use App\Services\Seo\SeoResolver;
use App\Services\Site\HomeContentResolver;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
            ],
            'seoDefaults' => fn () => app(SeoResolver::class)->defaults(),
            'campaign' => fn () => app(HomeContentResolver::class)->shell(),
            'tracking' => [
                'gtmId' => trim((string) config('tracking.gtm_id', '')),
                'gtmLoadStrategy' => trim((string) config('tracking.gtm_load_strategy', 'delayed')) ?: 'delayed',
                'hotjarId' => trim((string) config('tracking.hotjar_id', '')),
                'hotjarLoadStrategy' => trim((string) config('tracking.hotjar_load_strategy', 'idle')) ?: 'idle',
                'metaPixelId' => trim((string) config('tracking.meta_pixel_id', '')),
                'metaPixelLoadStrategy' => trim((string) config('tracking.meta_pixel_load_strategy', 'delayed')) ?: 'delayed',
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
