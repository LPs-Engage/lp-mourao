<?php

namespace App\Http\Controllers;

use App\Http\Requests\LandingPage\LandingPageLeadFormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LandingPageLeadController extends Controller
{
    public function __construct(private LandingPageController $landingPageController) {}

    public function store(string $slug, LandingPageLeadFormRequest $request): RedirectResponse
    {
        $landingPage = $this->landingPageController->activeBySlug($slug);

        if ($landingPage === null) {
            abort(404);
        }

        $data = $request->validated();
        $pageUrl = $data['page_url'] ?? $request->headers->get('referer');

        if (! is_string($pageUrl) || filter_var($pageUrl, FILTER_VALIDATE_URL) === false) {
            $pageUrl = null;
        }

        $queryParameters = [];
        $queryString = $pageUrl !== null ? parse_url($pageUrl, PHP_URL_QUERY) : null;

        if (is_string($queryString)) {
            parse_str($queryString, $queryParameters);
        }

        $utm = static function (string $key) use ($queryParameters): ?string {
            $value = $queryParameters[$key] ?? null;

            return is_string($value) ? $value : null;
        };

        $payload = [
            'slug' => $landingPage['slug'],
            'source' => 'lp/'.$landingPage['slug'],
            'page_url' => $pageUrl,
            'utm_source' => $utm('utm_source'),
            'utm_medium' => $utm('utm_medium'),
            'utm_campaign' => $utm('utm_campaign'),
            'utm_content' => $utm('utm_content'),
            'utm_term' => $utm('utm_term'),
            'submitted_at' => now()->toIso8601String(),
            'name' => $data['name'],
            'phone' => $data['phone'],
            'lgpd_accepted' => true,
        ];

        $webhookUrl = $this->resolveWebhookUrl();

        if ($webhookUrl === null) {
            Log::info('Lead de landing page recebido (webhook não configurado).', [
                'slug' => $landingPage['slug'],
                'name' => $data['name'],
            ]);

            return $this->redirectAfterSuccess($landingPage['slug']);
        }

        $http = Http::timeout((int) config('services.lp_lead.timeout', 10))
            ->acceptJson()
            ->asJson();

        if (! config('services.lp_lead.verify_ssl', true)) {
            $http = $http->withoutVerifying();
        }

        try {
            $response = $http->post($webhookUrl, $payload);

            if ($response->successful()) {
                return $this->redirectAfterSuccess($landingPage['slug']);
            }

            Log::warning('Webhook do lead da landing page retornou erro.', [
                'slug' => $landingPage['slug'],
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Falha ao enviar lead da landing page ao webhook.', [
                'slug' => $landingPage['slug'],
                'message' => $exception->getMessage(),
            ]);
        }

        return back()
            ->withInput()
            ->withErrors([
                'form' => 'Não foi possível enviar seu cadastro agora. Tente novamente em instantes.',
            ]);
    }

    private function redirectAfterSuccess(string $slug): RedirectResponse
    {
        return redirect()->route('lp.thank-you', ['slug' => $slug]);
    }

    private function resolveWebhookUrl(): ?string
    {
        $url = config('services.lp_lead.webhook_url');

        if (! is_string($url)) {
            return null;
        }

        $url = trim($url);

        return $url === '' ? null : $url;
    }
}
