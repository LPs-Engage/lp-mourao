<?php

namespace App\Services\Seo;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SeoResolver
{
    /**
     * @return array<string, mixed>
     */
    public function defaults(): array
    {
        return [
            'siteName' => $this->resolveSiteName(),
            'titleSuffix' => (string) config('seo.title_suffix'),
            'baseUrl' => rtrim($this->preferHttps((string) config('seo.base_url', config('app.url'))), '/'),
            'defaultOgImage' => $this->absoluteUrl((string) config('seo.default_og_image')),
            'twitterHandle' => (string) config('seo.twitter_handle'),
            'locale' => (string) config('seo.locale'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function forSitePage(string $pageKey): array
    {
        $pages = $this->loadSitePages();

        if (! isset($pages[$pageKey])) {
            abort(500, "SEO config missing for site page: {$pageKey}");
        }

        $config = $pages[$pageKey];

        return $this->resolve([
            'title' => (string) $config['title'],
            'description' => (string) $config['description'],
            'canonical' => (string) ($config['path'] ?? $this->defaultPathForSitePage($pageKey)),
            'robots' => (string) ($config['robots'] ?? 'index,follow'),
            'ogImage' => $config['ogImage'] ?? null,
            'ogType' => (string) ($config['ogType'] ?? 'website'),
            'schema' => (string) ($config['schema'] ?? 'WebPage'),
        ]);
    }

    /**
     * @param  array<string, mixed>  $landingPage
     * @return array<string, mixed>
     */
    public function forLandingPage(array $landingPage, ?string $canonicalPath = null): array
    {
        $seo = is_array($landingPage['seo'] ?? null) ? $landingPage['seo'] : [];
        $slug = (string) $landingPage['slug'];

        $title = (string) ($seo['title'] ?? $this->stripLegacyTitleSuffix((string) ($landingPage['seoTitle'] ?? $landingPage['productName'] ?? '')));
        $description = (string) ($seo['description'] ?? $landingPage['seoDescription'] ?? '');

        return $this->resolve([
            'title' => $title,
            'description' => $description,
            'canonical' => $canonicalPath ?? '/'.$slug,
            'robots' => (string) ($seo['robots'] ?? 'index,follow'),
            'ogImage' => $seo['ogImage'] ?? null,
            'ogType' => (string) ($seo['ogType'] ?? 'website'),
            'schema' => (string) ($seo['schema'] ?? 'WebPage'),
        ]);
    }

    /**
     * @param  array<string, mixed>  $landingPage
     * @param  array<string, mixed>  $thankYouSeo
     * @return array<string, mixed>
     */
    public function forLandingPageThankYou(array $landingPage, array $thankYouSeo = []): array
    {
        $slug = (string) $landingPage['slug'];
        $seo = is_array($landingPage['seo'] ?? null) ? $landingPage['seo'] : [];

        $thankYou = is_array($landingPage['thankYou'] ?? null) ? $landingPage['thankYou'] : [];

        $title = (string) ($thankYouSeo['title'] ?? $thankYou['title'] ?? 'Obrigado');
        $description = (string) ($thankYouSeo['description'] ?? $seo['description'] ?? $landingPage['seoDescription'] ?? '');

        return $this->resolve([
            'title' => $title,
            'description' => $description,
            'canonical' => '/'.$slug.'/obrigado',
            'robots' => (string) ($thankYouSeo['robots'] ?? 'noindex,nofollow'),
            'ogImage' => $thankYouSeo['ogImage'] ?? $seo['ogImage'] ?? null,
            'ogType' => 'website',
            'schema' => null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function forLandingPageNotFound(): array
    {
        return $this->resolve([
            'title' => 'Página não encontrada',
            'description' => 'O link que você acessou não existe ou não está mais disponível.',
            'canonical' => '/',
            'robots' => 'noindex,nofollow',
            'ogType' => 'website',
            'schema' => null,
        ]);
    }

    /**
     * @return list<string>
     */
    public function landingPageSlugs(): array
    {
        $data = $this->loadLandingPagesData();
        $slugs = [];

        foreach ($data['landingPages'] as $landingPage) {
            if (! is_array($landingPage) || ! isset($landingPage['slug'])) {
                continue;
            }

            if (($landingPage['active'] ?? true) === false) {
                continue;
            }

            $seo = is_array($landingPage['seo'] ?? null) ? $landingPage['seo'] : [];
            $robots = strtolower((string) ($seo['robots'] ?? 'index,follow'));

            if (str_contains($robots, 'noindex')) {
                continue;
            }

            $slugs[] = (string) $landingPage['slug'];
        }

        return $slugs;
    }

    /**
     * @return list<array{slug: string, productName: string, description: string, image: string|null}>
     */
    public function listActiveForVitrine(): array
    {
        $data = $this->loadLandingPagesData();
        $cards = [];

        foreach ($data['landingPages'] as $landingPage) {
            if (! is_array($landingPage) || ! isset($landingPage['slug'], $landingPage['productName'])) {
                continue;
            }

            if (($landingPage['active'] ?? true) === false) {
                continue;
            }

            $seo = is_array($landingPage['seo'] ?? null) ? $landingPage['seo'] : [];
            $description = '';

            if (isset($seo['description']) && is_string($seo['description'])) {
                $description = trim($seo['description']);
            }

            if ($description === '' && isset($landingPage['heroSubtitle']) && is_string($landingPage['heroSubtitle'])) {
                $description = trim($landingPage['heroSubtitle']);
            }

            $image = null;

            if (isset($seo['ogImage']) && is_string($seo['ogImage']) && trim($seo['ogImage']) !== '') {
                $image = trim($seo['ogImage']);
            }

            $cards[] = [
                'slug' => (string) $landingPage['slug'],
                'productName' => (string) $landingPage['productName'],
                'description' => $description,
                'image' => $image,
            ];
        }

        return $cards;
    }

    /**
     * @return list<array{loc: string, lastmod?: string}>
     */
    public function sitemapUrls(): array
    {
        $urls = [];
        $now = now()->toAtomString();

        foreach (config('seo.sitemap.institutional_paths', []) as $path => $pageKey) {
            $urls[] = [
                'loc' => $this->absoluteUrl($path === '/' ? '/' : $path),
                'lastmod' => $now,
            ];
        }

        foreach ($this->landingPageSlugs() as $slug) {
            $urls[] = [
                'loc' => $this->absoluteUrl('/'.$slug),
                'lastmod' => $now,
            ];
        }

        return $urls;
    }

    public function buildTitle(string $title): string
    {
        $title = trim($title);

        if ($title === '') {
            return (string) config('seo.title_suffix');
        }

        $suffix = ' | '.config('seo.title_suffix');

        if (Str::contains($title, '|')) {
            return $title;
        }

        return $title.$suffix;
    }

    public function absoluteUrl(string $pathOrUrl): string
    {
        $pathOrUrl = trim($pathOrUrl);

        if ($pathOrUrl === '') {
            return rtrim($this->preferHttps((string) config('seo.base_url', config('app.url'))), '/');
        }

        if (preg_match('/^https?:\/\//i', $pathOrUrl)) {
            return $this->preferHttps($pathOrUrl);
        }

        $base = rtrim($this->preferHttps((string) config('seo.base_url', config('app.url'))), '/');
        $path = '/'.ltrim($pathOrUrl, '/');

        if ($path === '//') {
            return $base.'/';
        }

        return $base.$path;
    }

    private function resolveSiteName(): string
    {
        $name = trim((string) config('seo.site_name'));

        if ($name === '' || str_contains($name, '${')) {
            return (string) config('seo.title_suffix', 'Lucas Mourão');
        }

        return $name;
    }

    private function preferHttps(string $url): string
    {
        $url = trim($url);

        if (! str_starts_with($url, 'http://')) {
            return $url;
        }

        $host = (string) (parse_url($url, PHP_URL_HOST) ?? '');

        if (in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
            return $url;
        }

        return 'https://'.substr($url, strlen('http://'));
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function resolve(array $input): array
    {
        $fullTitle = $this->buildTitle((string) $input['title']);
        $description = $this->truncateDescription((string) $input['description']);
        $canonical = $this->absoluteUrl((string) $input['canonical']);
        $ogImage = isset($input['ogImage']) && is_string($input['ogImage']) && $input['ogImage'] !== ''
            ? $this->absoluteUrl($input['ogImage'])
            : $this->absoluteUrl((string) config('seo.default_og_image'));

        $result = [
            'title' => $fullTitle,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => (string) $input['robots'],
            'ogTitle' => $fullTitle,
            'ogDescription' => $description,
            'ogImage' => $ogImage,
            'ogUrl' => $canonical,
            'ogType' => (string) ($input['ogType'] ?? 'website'),
            'twitterCard' => 'summary_large_image',
        ];

        if (! empty($input['schema'])) {
            $result['jsonLd'] = $this->buildJsonLd(
                (string) $input['schema'],
                $fullTitle,
                $description,
                $canonical,
                $ogImage,
            );
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildJsonLd(string $schema, string $title, string $description, string $url, string $ogImage): array
    {
        $defaults = $this->defaults();
        $siteName = $defaults['siteName'];
        $baseUrl = $defaults['baseUrl'];

        return match ($schema) {
            'Organization' => [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => $siteName,
                'url' => $baseUrl.'/',
                'description' => $description,
                'logo' => $defaults['defaultOgImage'],
            ],
            'Course' => [
                '@context' => 'https://schema.org',
                '@type' => 'Course',
                'name' => $title,
                'description' => $description,
                'url' => $url,
                'provider' => [
                    '@type' => 'Organization',
                    'name' => $siteName,
                    'url' => $baseUrl.'/',
                ],
            ],
            'Event' => [
                '@context' => 'https://schema.org',
                '@type' => 'Event',
                'name' => $title,
                'description' => $description,
                'url' => $url,
                'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
                'organizer' => [
                    '@type' => 'Organization',
                    'name' => $siteName,
                    'url' => $baseUrl.'/',
                ],
            ],
            default => array_filter([
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $title,
                'description' => $description,
                'url' => $url,
                'isPartOf' => [
                    '@type' => 'WebSite',
                    'name' => $siteName,
                    'url' => $baseUrl.'/',
                ],
                'primaryImageOfPage' => $ogImage !== '' ? [
                    '@type' => 'ImageObject',
                    'url' => $ogImage,
                ] : null,
            ], fn ($value) => $value !== null),
        };
    }

    private function truncateDescription(string $description): string
    {
        $description = trim(preg_replace('/\s+/', ' ', $description) ?? '');

        if (strlen($description) <= 160) {
            return $description;
        }

        return rtrim(substr($description, 0, 157)).'...';
    }

    private function stripLegacyTitleSuffix(string $title): string
    {
        $title = trim($title);
        $suffix = ' | '.config('seo.title_suffix');

        if (str_ends_with($title, $suffix)) {
            return trim(substr($title, 0, -strlen($suffix)));
        }

        return $title;
    }

    private function defaultPathForSitePage(string $pageKey): string
    {
        return match ($pageKey) {
            'home' => '/',
            default => '/'.$pageKey,
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function loadSitePages(): array
    {
        $path = resource_path('data/site-pages.json');

        if (! File::exists($path)) {
            return [];
        }

        $decoded = json_decode(File::get($path), true);

        if (! is_array($decoded) || ! isset($decoded['pages']) || ! is_array($decoded['pages'])) {
            return [];
        }

        return $decoded['pages'];
    }

    /**
     * @return array<string, mixed>
     */
    private function loadLandingPagesData(): array
    {
        $path = resource_path('data/landing-pages.json');

        if (! File::exists($path)) {
            return ['landingPages' => []];
        }

        $decoded = json_decode(File::get($path), true);

        if (! is_array($decoded) || ! isset($decoded['landingPages'])) {
            return ['landingPages' => []];
        }

        return $decoded;
    }
}
