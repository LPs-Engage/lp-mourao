<?php

use App\Services\Seo\SeoResolver;

use function Pest\Laravel\get;

test('sitemap returns xml with home url and excludes noindex landing pages', function () {
    $response = get(route('sitemap'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
    $response->assertSee('<urlset', false);
    $response->assertSee(config('app.url'), false);
    $response->assertDontSee('/exemplo', false);
    $response->assertDontSee('/obrigado', false);
});

test('thank you page includes noindex robots directive in seo props', function () {
    get(route('obrigado'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('site/thank-you')
            ->where('seo.robots', 'noindex,nofollow')
        );
});

test('landing page thank you includes noindex robots directive', function () {
    get(route('lp.thank-you', ['slug' => 'exemplo']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('landing-pages/thank-you')
            ->where('seo.robots', 'noindex,nofollow')
        );
});

test('landing page includes canonical and structured data in seo props', function () {
    get(route('lp.show', ['slug' => 'exemplo']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('landing-pages/show')
            ->has('seo')
            ->where('seo.robots', 'noindex,nofollow')
            ->has('seo.canonical')
            ->has('seo.jsonLd')
        );
});

test('home page includes webpage structured data in seo props', function () {
    get(route('home'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('site/home')
            ->has('seo.jsonLd')
            ->where('seo.jsonLd.@type', 'WebPage')
            ->has('content')
        );
});

test('home seo title keeps the campaign name without duplicating suffix', function () {
    get(route('home'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('site/home')
            ->where('seo.title', 'Lucas Mourão | 55500')
        );
});

test('nonexistent landing page is not in sitemap and returns 404', function () {
    get(route('lp.show', ['slug' => 'slug-inexistente']))
        ->assertNotFound()
        ->assertInertia(fn ($page) => $page
            ->component('landing-pages/not-found')
            ->where('seo.robots', 'noindex,nofollow')
        );

    $sitemap = get(route('sitemap'));
    $sitemap->assertDontSee('/slug-inexistente', false);
});

test('inactive landing page returns 404 and is excluded from sitemap', function () {
    get(route('lp.show', ['slug' => 'lp-desativada']))
        ->assertNotFound()
        ->assertInertia(fn ($page) => $page
            ->component('landing-pages/not-found')
            ->where('seo.robots', 'noindex,nofollow')
        );

    get(route('lp.thank-you', ['slug' => 'lp-desativada']))
        ->assertNotFound()
        ->assertInertia(fn ($page) => $page
            ->component('landing-pages/not-found')
            ->where('seo.robots', 'noindex,nofollow')
        );

    get(route('sitemap'))
        ->assertOk()
        ->assertDontSee('/lp-desativada', false);
});

test('home html includes open graph tags for social crawlers', function () {
    config([
        'seo.base_url' => 'https://www.lucasmourao.com.br',
        'seo.site_name' => 'Lucas Mourão',
        'app.name' => 'Laravel',
    ]);

    $response = get(route('home'));

    $response->assertOk();
    $response->assertSee('property="og:title"', false);
    $response->assertSee('Lucas Mourão | 55500', false);
    $response->assertSee('property="og:image"', false);
    $response->assertSee('hero-backgroundV2.png', false);
    $response->assertSee('property="og:site_name"', false);
    $response->assertDontSee('<title>Laravel</title>', false);
});

test('seo resolver upgrades non-local http urls to https', function () {
    config([
        'seo.base_url' => 'http://lucasmourao.com.br',
        'seo.site_name' => '${APP_NAME}',
        'seo.title_suffix' => 'Lucas Mourão',
    ]);

    $resolver = app(SeoResolver::class);

    expect($resolver->defaults()['siteName'])->toBe('Lucas Mourão')
        ->and($resolver->defaults()['baseUrl'])->toBe('https://lucasmourao.com.br')
        ->and($resolver->absoluteUrl('/images/hero-backgroundV2.png'))
        ->toBe('https://lucasmourao.com.br/images/hero-backgroundV2.png');
});
