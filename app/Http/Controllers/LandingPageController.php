<?php

namespace App\Http\Controllers;

use App\Services\Content\FaqResolver;
use App\Services\Content\PageContentSanitizer;
use App\Services\Seo\SeoResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class LandingPageController extends Controller
{
    private const ALLOWED_TEMPLATES = ['hero-only', 'minimal-lead'];

    public function __construct(
        private SeoResolver $seoResolver,
        private PageContentSanitizer $contentSanitizer,
        private FaqResolver $faqResolver,
    ) {}

    public function show(string $slug, Request $request): Response|HttpResponse
    {
        return $this->renderShow($slug, $request);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function activeBySlug(string $slug): ?array
    {
        return $this->resolveLandingPage($slug);
    }

    private function renderShow(string $slug, Request $request): Response|HttpResponse
    {
        $landingPage = $this->resolveLandingPage($slug);

        if ($landingPage === null) {
            return $this->renderNotFound($request);
        }

        return Inertia::render('landing-pages/show', [
            'landingPage' => $landingPage,
            'seo' => $this->seoResolver->forLandingPage($landingPage),
        ]);
    }

    public function thankYou(string $slug, Request $request): Response|HttpResponse
    {
        $landingPage = $this->resolveLandingPage($slug);

        if ($landingPage === null) {
            return $this->renderNotFound($request);
        }

        $thankYouSeo = is_array($landingPage['thankYouSeo'] ?? null) ? $landingPage['thankYouSeo'] : [];

        return Inertia::render('landing-pages/thank-you', [
            'landingPage' => [
                'slug' => $landingPage['slug'],
                'productName' => $landingPage['productName'],
                'template' => $landingPage['template'],
                'theme' => $landingPage['theme'],
            ],
            'thankYou' => $landingPage['thankYou'],
            'seo' => $this->seoResolver->forLandingPageThankYou($landingPage, $thankYouSeo),
        ]);
    }

    private function renderNotFound(Request $request): HttpResponse
    {
        return Inertia::render('landing-pages/not-found', [
            'seo' => $this->seoResolver->forLandingPageNotFound(),
        ])->toResponse($request)->setStatusCode(404);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function resolveLandingPage(string $slug): ?array
    {
        $slug = strtolower(trim($slug));

        if (! preg_match('/^[a-z0-9-]+$/', $slug)) {
            return null;
        }

        $landingPage = $this->findLandingPageBySlug($slug);

        if ($landingPage === null) {
            return null;
        }

        if (($landingPage['active'] ?? true) === false) {
            return null;
        }

        return $this->sanitizeLandingPage($landingPage);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findLandingPageBySlug(string $slug): ?array
    {
        $data = $this->loadLandingPagesData();

        foreach ($data['landingPages'] as $landingPage) {
            if (($landingPage['slug'] ?? null) === $slug) {
                return $landingPage;
            }
        }

        return null;
    }

    /**
     * @return array{landingPages: list<array<string, mixed>>}
     */
    private function loadLandingPagesData(): array
    {
        $path = resource_path('data/landing-pages.json');

        if (! File::exists($path)) {
            return ['landingPages' => []];
        }

        $decoded = json_decode(File::get($path), true);

        if (! is_array($decoded) || ! isset($decoded['landingPages']) || ! is_array($decoded['landingPages'])) {
            return ['landingPages' => []];
        }

        $pages = [];

        foreach ($decoded['landingPages'] as $landingPage) {
            if (is_array($landingPage)) {
                $pages[] = $landingPage;
            }
        }

        return ['landingPages' => $pages];
    }

    /**
     * @param  array<string, mixed>  $landingPage
     * @return array<string, mixed>|null
     */
    private function sanitizeLandingPage(array $landingPage): ?array
    {
        $required = [
            'slug',
            'productName',
            'template',
            'heroTitle',
            'heroSubtitle',
            'ctaLabel',
            'ctaHref',
            'sections',
            'thankYou',
        ];

        foreach ($required as $field) {
            if (! array_key_exists($field, $landingPage)) {
                return null;
            }
        }

        if (! is_array($landingPage['seo'] ?? null)) {
            return null;
        }

        $seo = $landingPage['seo'];

        foreach (['title', 'description'] as $field) {
            if (! isset($seo[$field]) || ! is_string($seo[$field])) {
                return null;
            }
        }

        $template = (string) $landingPage['template'];

        if (! in_array($template, self::ALLOWED_TEMPLATES, true)) {
            return null;
        }

        if (! is_array($landingPage['sections']) || ! is_array($landingPage['thankYou'])) {
            return null;
        }

        $thankYou = $landingPage['thankYou'];

        foreach (['title', 'message', 'ctaLabel', 'ctaHref'] as $field) {
            if (! isset($thankYou[$field]) || ! is_string($thankYou[$field])) {
                return null;
            }
        }

        $sections = $this->sanitizeSections($landingPage['sections']);
        $contentSections = $this->contentSanitizer->sanitizeContentSections($landingPage['contentSections'] ?? []);
        $slides = $this->contentSanitizer->sanitizeSlides($landingPage['slides'] ?? []);
        $slideAutoPlayMs = $this->contentSanitizer->sanitizeSlideAutoPlayMs($landingPage['slideAutoPlayMs'] ?? null);
        $thankYouSeo = is_array($landingPage['thankYouSeo'] ?? null) ? $landingPage['thankYouSeo'] : [];

        return [
            'slug' => (string) $landingPage['slug'],
            'productName' => (string) $landingPage['productName'],
            'template' => $template,
            'theme' => (string) ($landingPage['theme'] ?? 'default'),
            'seo' => [
                'title' => (string) $seo['title'],
                'description' => (string) $seo['description'],
                'robots' => (string) ($seo['robots'] ?? 'index,follow'),
                'ogImage' => isset($seo['ogImage']) ? (string) $seo['ogImage'] : null,
                'ogType' => (string) ($seo['ogType'] ?? 'website'),
                'schema' => (string) ($seo['schema'] ?? 'WebPage'),
            ],
            'thankYouSeo' => $thankYouSeo,
            'heroTitle' => (string) $landingPage['heroTitle'],
            'heroSubtitle' => (string) $landingPage['heroSubtitle'],
            'ctaLabel' => (string) $landingPage['ctaLabel'],
            'ctaHref' => $this->contentSanitizer->sanitizeUrl((string) $landingPage['ctaHref']),
            'sections' => $sections,
            'contentSections' => $contentSections,
            'slides' => $slides,
            'slideAutoPlayMs' => $slideAutoPlayMs,
            'faq' => $this->faqResolver->resolve($landingPage['faq'] ?? null),
            'thankYou' => [
                'title' => (string) $thankYou['title'],
                'message' => (string) $thankYou['message'],
                'ctaLabel' => (string) $thankYou['ctaLabel'],
                'ctaHref' => $this->contentSanitizer->sanitizeUrl((string) $thankYou['ctaHref']),
                'secondaryCtaLabel' => isset($thankYou['secondaryCtaLabel']) && is_string($thankYou['secondaryCtaLabel'])
                    ? (string) $thankYou['secondaryCtaLabel']
                    : null,
                'secondaryCtaHref' => isset($thankYou['secondaryCtaHref']) && is_string($thankYou['secondaryCtaHref'])
                    ? $this->contentSanitizer->sanitizeUrl((string) $thankYou['secondaryCtaHref'])
                    : null,
            ],
        ];
    }

    /**
     * @return list<array{title: string, body: string}>
     */
    private function sanitizeSections(mixed $rawSections): array
    {
        if (! is_array($rawSections)) {
            return [];
        }

        $sections = [];

        foreach ($rawSections as $section) {
            if (! is_array($section) || ! isset($section['title'], $section['body'])) {
                continue;
            }

            $sections[] = [
                'title' => (string) $section['title'],
                'body' => (string) $section['body'],
            ];
        }

        return $sections;
    }
}
