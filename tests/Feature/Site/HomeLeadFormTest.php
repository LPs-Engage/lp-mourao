<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

function validHomeLeadPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Maria Silva Souza',
        'phone' => '11987654321',
        'city' => 'Praia Grande',
        'neighborhood' => 'Boqueirão',
        'lgpd' => '1',
        'page_url' => 'https://example.test/?utm_source=teste',
        'utm_source' => 'teste',
    ], $overrides);
}

test('cadastro válido permanece na home com mensagem de sucesso', function () {
    config(['services.home_lead.webhook_url' => '']);

    $this->from(route('home'))
        ->post(route('cadastro.store'), validHomeLeadPayload())
        ->assertRedirect(route('home'))
        ->assertSessionHas('success')
        ->assertSessionHasNoErrors();
});

test('cadastro válido envia payload ao webhook configurado', function () {
    config(['services.home_lead.webhook_url' => 'https://hooks.example.test/home-lead']);

    Http::fake([
        'hooks.example.test/*' => Http::response(['ok' => true], 200),
    ]);

    $this->from(route('home'))
        ->post(route('cadastro.store'), validHomeLeadPayload())
        ->assertRedirect(route('home'));

    Http::assertSent(function ($request) {
        return $request->url() === 'https://hooks.example.test/home-lead'
            && $request['source'] === 'home'
            && $request['name'] === 'Maria Silva Souza'
            && $request['city'] === 'Praia Grande'
            && $request['neighborhood'] === 'Boqueirão'
            && $request['utm_source'] === 'teste';
    });
});

test('webhook com falha retorna erro sem redirecionar para obrigado', function () {
    config(['services.home_lead.webhook_url' => 'https://hooks.example.test/home-lead']);

    Http::fake([
        'hooks.example.test/*' => Http::response('error', 500),
    ]);

    $this->from(route('home'))
        ->post(route('cadastro.store'), validHomeLeadPayload())
        ->assertRedirect(route('home'))
        ->assertSessionHasErrors('form');
});

test('nome e telefone com acentos e máscara passam', function () {
    $this->from(route('home'))
        ->post(route('cadastro.store'), validHomeLeadPayload([
            'name' => "Maria D'Ávila Conceição-Souza",
            'phone' => '(11) 98765-4321',
        ]))
        ->assertSessionHasNoErrors();
});

test('payload XSS no nome é rejeitado', function (string $payload) {
    $this->post(route('cadastro.store'), validHomeLeadPayload(['name' => $payload]))
        ->assertSessionHasErrors('name');
})->with([
    '<script>alert(1)</script>',
    '<b>negrito</b>',
]);

test('cadastro sem cidade, bairro ou LGPD é rejeitado', function () {
    $this->post(route('cadastro.store'), validHomeLeadPayload([
        'city' => '',
        'neighborhood' => '',
        'lgpd' => '',
    ]))->assertSessionHasErrors(['city', 'neighborhood', 'lgpd']);
});

test('honeypot preenchido é rejeitado com 422', function () {
    $this->postJson(route('cadastro.store'), validHomeLeadPayload(['website' => 'http://spam.example.com']))
        ->assertStatus(422)
        ->assertJsonValidationErrors('website');
});

test('rate limit bloqueia o sexto envio no mesmo minuto', function () {
    foreach (range(1, 5) as $i) {
        $this->post(route('cadastro.store'), validHomeLeadPayload())->assertStatus(302);
    }

    $this->post(route('cadastro.store'), validHomeLeadPayload())->assertStatus(429);
});

test('rota de cadastro usa CSRF e throttle dedicado', function () {
    $route = Route::getRoutes()->getByName('cadastro.store');

    expect($route)->not->toBeNull()
        ->and($route->gatherMiddleware())->toContain('web')
        ->and($route->gatherMiddleware())->toContain('throttle:home-lead-form');
});
