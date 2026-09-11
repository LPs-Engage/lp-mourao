<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\Seo\SeoResolver;
use App\Services\Site\HomeContentResolver;
use Inertia\Inertia;
use Inertia\Response;

class SitePageController extends Controller
{
    public function __construct(
        private SeoResolver $seoResolver,
        private HomeContentResolver $homeContentResolver,
    ) {}

    public function home(): Response
    {
        return Inertia::render('site/home', [
            'seo' => $this->seoResolver->forSitePage('home'),
            'content' => $this->homeContentResolver->forHome(),
        ]);
    }

    public function thankYou(): Response
    {
        return Inertia::render('site/thank-you', [
            'seo' => $this->seoResolver->forSitePage('obrigado'),
            'whatsappGroupUrl' => $this->homeContentResolver->whatsappGroupUrl(),
        ]);
    }
}
