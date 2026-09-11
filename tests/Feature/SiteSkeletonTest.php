<?php

use function Pest\Laravel\get;

test('home page returns successful response', function () {
    get(route('home'))->assertOk();
});

test('home renders campaign page', function () {
    get(route('home'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('site/home')
            ->has('content.hero')
            ->has('content.movimento')
        );
});

test('obrigado page returns successful response', function () {
    get(route('obrigado'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('site/thank-you'));
});

test('shared tracking props expose meta pixel id from config', function () {
    config(['tracking.meta_pixel_id' => '735261366271042']);

    get(route('home'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('tracking.metaPixelId', '735261366271042')
        );
});

test('valid landing page returns successful response', function () {
    get(route('lp.show', ['slug' => 'exemplo']))->assertOk();
});

test('valid thank you page returns successful response', function () {
    get(route('lp.thank-you', ['slug' => 'exemplo']))->assertOk();
});

test('nonexistent landing page returns 404', function () {
    get(route('lp.show', ['slug' => 'slug-inexistente']))
        ->assertNotFound()
        ->assertInertia(fn ($page) => $page
            ->component('landing-pages/not-found')
            ->where('seo.robots', 'noindex,nofollow')
        );
});

test('nonexistent thank you page returns 404', function () {
    get(route('lp.thank-you', ['slug' => 'slug-inexistente']))
        ->assertNotFound()
        ->assertInertia(fn ($page) => $page
            ->component('landing-pages/not-found')
            ->where('seo.robots', 'noindex,nofollow')
        );
});

test('legacy /lp/{slug} permanently redirects to /{slug}', function () {
    get('/lp/exemplo')
        ->assertRedirect('/exemplo')
        ->assertStatus(301);
});

test('legacy /lp/{slug}/obrigado permanently redirects to /{slug}/obrigado', function () {
    get('/lp/exemplo/obrigado')
        ->assertRedirect('/exemplo/obrigado')
        ->assertStatus(301);
});

test('clean landing page urls resolve without /lp prefix', function () {
    get('/exemplo')->assertOk();
    get('/exemplo/obrigado')->assertOk();
});

test('reserved paths are not captured by landing page catch-all', function () {
    get('/obrigado')->assertOk();
    get('/login')->assertOk();
});
