<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LandingPageLeadController;
use App\Http\Controllers\Site\HomeLeadController;
use App\Http\Controllers\Site\SitePageController;
use App\Http\Controllers\SitemapController;
use App\Support\LandingPageRoutes;
use Illuminate\Support\Facades\Route;

Route::get('/', [SitePageController::class, 'home'])->name('home');
Route::get('/obrigado', [SitePageController::class, 'thankYou'])->name('obrigado');

Route::post('/cadastro', [HomeLeadController::class, 'store'])
    ->middleware('throttle:home-lead-form')
    ->name('cadastro.store');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';

$lpSlug = LandingPageRoutes::slugConstraint();

Route::get('/{slug}/obrigado', [LandingPageController::class, 'thankYou'])
    ->where('slug', $lpSlug)
    ->name('lp.thank-you');

Route::post('/{slug}/lead', [LandingPageLeadController::class, 'store'])
    ->where('slug', $lpSlug)
    ->middleware('throttle:lp-lead-form')
    ->name('lp.lead.store');

Route::get('/{slug}', [LandingPageController::class, 'show'])
    ->where('slug', $lpSlug)
    ->name('lp.show');

Route::get('/lp/{slug}/obrigado', function (string $slug) {
    return redirect()->route('lp.thank-you', ['slug' => $slug], 301);
})->where('slug', '[a-z0-9-]+');

Route::get('/lp/{slug}', function (string $slug) {
    return redirect()->route('lp.show', ['slug' => $slug], 301);
})->where('slug', '[a-z0-9-]+');

Route::post('/lp/{slug}/lead', [LandingPageLeadController::class, 'store'])
    ->where('slug', '[a-z0-9-]+')
    ->middleware('throttle:lp-lead-form');
