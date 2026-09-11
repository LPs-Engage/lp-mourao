<?php

use Illuminate\Support\Facades\Http;

function validLpLeadPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'João da Silva',
        'phone' => '11987654321',
        'lgpd' => '1',
        'page_url' => 'https://example.test/exemplo?utm_source=ads',
    ], $overrides);
}

test('lead de LP válido redireciona para obrigado sem webhook', function () {
    config(['services.lp_lead.webhook_url' => '']);

    $this->from('/exemplo')
        ->post('/exemplo/lead', validLpLeadPayload())
        ->assertRedirect(route('lp.thank-you', ['slug' => 'exemplo']))
        ->assertSessionHasNoErrors();
});

test('lead de LP envia payload ao webhook', function () {
    config(['services.lp_lead.webhook_url' => 'https://hooks.example.test/lp-lead']);

    Http::fake([
        'hooks.example.test/*' => Http::response(['ok' => true], 200),
    ]);

    $this->post('/exemplo/lead', validLpLeadPayload())
        ->assertRedirect(route('lp.thank-you', ['slug' => 'exemplo']));

    Http::assertSent(function ($request) {
        return $request->url() === 'https://hooks.example.test/lp-lead'
            && $request['slug'] === 'exemplo'
            && $request['name'] === 'João da Silva';
    });
});

test('lead em slug inexistente retorna 404', function () {
    $this->post('/slug-inexistente/lead', validLpLeadPayload())
        ->assertNotFound();
});
