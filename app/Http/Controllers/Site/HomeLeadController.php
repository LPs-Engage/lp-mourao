<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\HomeLeadFormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HomeLeadController extends Controller
{
    public function store(HomeLeadFormRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $webhookUrl = $this->resolveWebhookUrl();

        $payload = [
            'source' => 'home',
            'submitted_at' => now()->toIso8601String(),
            'name' => $data['name'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'neighborhood' => $data['neighborhood'],
            'lgpd_accepted' => true,
            'page_url' => $data['page_url'] ?? null,
            'utm_source' => $data['utm_source'] ?? null,
            'utm_medium' => $data['utm_medium'] ?? null,
            'utm_campaign' => $data['utm_campaign'] ?? null,
            'utm_term' => $data['utm_term'] ?? null,
            'utm_content' => $data['utm_content'] ?? null,
        ];

        if ($webhookUrl === null) {
            Log::info('Cadastro da home recebido (webhook não configurado).', [
                'name' => $data['name'],
                'city' => $data['city'],
            ]);

            return $this->redirectAfterSuccess();
        }

        $http = Http::timeout((int) config('services.home_lead.timeout', 10))
            ->acceptJson()
            ->asJson();

        if (! config('services.home_lead.verify_ssl', true)) {
            $http = $http->withoutVerifying();
        }

        try {
            $response = $http->post($webhookUrl, $payload);

            if ($response->successful()) {
                return $this->redirectAfterSuccess();
            }

            Log::warning('Webhook do cadastro da home retornou erro.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Falha ao enviar cadastro da home ao webhook.', [
                'message' => $exception->getMessage(),
            ]);
        }

        return back()
            ->withInput()
            ->withErrors([
                'form' => 'Não foi possível enviar seu cadastro agora. Tente novamente em instantes.',
            ]);
    }

    private function redirectAfterSuccess(): RedirectResponse
    {
        return back()->with('success', 'Cadastro realizado.');
    }

    private function resolveWebhookUrl(): ?string
    {
        $url = config('services.home_lead.webhook_url');

        if (! is_string($url)) {
            return null;
        }

        $url = trim($url);

        return $url === '' ? null : $url;
    }
}
