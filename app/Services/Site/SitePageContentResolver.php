<?php

namespace App\Services\Site;

use App\Services\Content\FaqResolver;
use App\Services\Content\PageContentSanitizer;
use Illuminate\Support\Facades\File;

class SitePageContentResolver
{
    public function __construct(
        private PageContentSanitizer $contentSanitizer,
        private FaqResolver $faqResolver,
    ) {}

    /**
     * @return array{
     *   slides: list<array<string, mixed>>,
     *   slideAutoPlayMs: int|null,
     *   contentSections: list<array<string, mixed>>,
     *   faq: array<string, mixed>|null
     * }
     */
    public function blocksFor(string $pageKey): array
    {
        $config = $this->pageConfig($pageKey);

        return [
            'slides' => $this->contentSanitizer->sanitizeSlides($config['slides'] ?? []),
            'slideAutoPlayMs' => $this->contentSanitizer->sanitizeSlideAutoPlayMs($config['slideAutoPlayMs'] ?? null),
            'contentSections' => $this->contentSanitizer->sanitizeContentSections($config['contentSections'] ?? []),
            'faq' => $this->faqResolver->resolve($config['faq'] ?? null),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function pageConfig(string $pageKey): array
    {
        $path = resource_path('data/site-pages.json');

        if (! File::exists($path)) {
            return [];
        }

        $decoded = json_decode(File::get($path), true);

        if (! is_array($decoded) || ! isset($decoded['pages'][$pageKey]) || ! is_array($decoded['pages'][$pageKey])) {
            return [];
        }

        return $decoded['pages'][$pageKey];
    }
}
