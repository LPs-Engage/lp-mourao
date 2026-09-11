<?php

namespace App\Services\Site;

use App\Services\Content\PageContentSanitizer;
use Illuminate\Support\Facades\File;

class HomeContentResolver
{
    public function __construct(private PageContentSanitizer $contentSanitizer) {}

    /**
     * @return array<string, mixed>
     */
    public function forHome(): array
    {
        $campaign = $this->campaign();

        return [
            'whatsappGroupUrl' => $this->whatsappGroupUrl(),
            'hero' => $this->hero($campaign['hero'] ?? []),
            'lucas' => $this->lucas($campaign['lucas'] ?? []),
            'trabalho' => $this->trabalho($campaign['trabalho'] ?? []),
            'projetos' => $this->projetosIntro($campaign['projetos'] ?? []),
            'agenda' => $this->agenda($campaign['agenda'] ?? []),
            'onde' => $this->onde($campaign['onde'] ?? []),
            'fatos' => $this->fatos($campaign['fatos'] ?? []),
            'baixada' => $this->baixada($campaign['baixada'] ?? []),
            'capitulo' => $this->capitulo($campaign['capitulo'] ?? []),
            'canal' => $this->canal($campaign['canal'] ?? []),
            'redes' => $this->redes($campaign['redes'] ?? []),
            'movimento' => $this->movimento($campaign['movimento'] ?? []),
            'footer' => $this->footer($campaign['footer'] ?? []),
        ];
    }

    /**
     * @return array{whatsappGroupUrl: string, footer: array<string, mixed>}
     */
    public function shell(): array
    {
        $campaign = $this->campaign();

        return [
            'whatsappGroupUrl' => $this->whatsappGroupUrl(),
            'footer' => $this->footer($campaign['footer'] ?? []),
        ];
    }

    public function whatsappGroupUrl(): string
    {
        $campaign = $this->campaign();
        $url = $campaign['whatsappGroupUrl'] ?? '';

        return is_string($url) ? $this->contentSanitizer->sanitizeUrl($url) : '/';
    }

    /**
     * @return array<string, mixed>
     */
    private function campaign(): array
    {
        $path = resource_path('data/site-pages.json');

        if (! File::exists($path)) {
            return [];
        }

        $decoded = json_decode(File::get($path), true);

        if (! is_array($decoded) || ! isset($decoded['campaign']) || ! is_array($decoded['campaign'])) {
            return [];
        }

        return $decoded['campaign'];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function hero(array $raw): array
    {
        return [
            'title' => $this->string($raw['title'] ?? ''),
            'image' => $this->contentSanitizer->sanitizeUrl($this->string($raw['image'] ?? '/images/hero-backgroundV2.png')),
            'imageMobile' => $this->contentSanitizer->sanitizeUrl($this->string($raw['imageMobile'] ?? '/images/hero-background-mobV2.png')),
            'imageAlt' => $this->string($raw['imageAlt'] ?? ''),
            'marqueeAlt' => $this->string($raw['marqueeAlt'] ?? ''),
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function lucas(array $raw): array
    {
        $items = [];

        foreach ($this->list($raw['items'] ?? null) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $items[] = [
                'icon' => $this->contentSanitizer->sanitizeUrl($this->string($item['icon'] ?? '')),
                'iconAlt' => $this->string($item['iconAlt'] ?? ''),
                'title' => $this->string($item['title'] ?? ''),
                'body' => $this->string($item['body'] ?? ''),
            ];
        }

        return [
            'eyebrow' => $this->string($raw['eyebrow'] ?? ''),
            'title' => $this->string($raw['title'] ?? ''),
            'titleAccent' => $this->string($raw['titleAccent'] ?? ''),
            'items' => $items,
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function trabalho(array $raw): array
    {
        $items = [];

        foreach ($this->list($raw['items'] ?? null) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $images = [];

            foreach ($this->list($item['images'] ?? null) as $image) {
                if (! is_array($image)) {
                    continue;
                }

                $images[] = [
                    'src' => $this->contentSanitizer->sanitizeUrl($this->string($image['src'] ?? '')),
                    'alt' => $this->string($image['alt'] ?? ''),
                ];
            }

            $items[] = [
                'title' => $this->string($item['title'] ?? ''),
                'body' => $this->string($item['body'] ?? ''),
                'images' => $images,
            ];
        }

        return [
            'eyebrow' => $this->string($raw['eyebrow'] ?? ''),
            'title' => $this->string($raw['title'] ?? ''),
            'items' => $items,
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function projetosIntro(array $raw): array
    {
        return [
            'eyebrow' => $this->string($raw['eyebrow'] ?? ''),
            'title' => $this->string($raw['title'] ?? ''),
            'body' => $this->listOfStrings($raw['body'] ?? []),
            'videoSrc' => $this->nullableUrl($raw['videoSrc'] ?? null),
            'videoPoster' => $this->nullableUrl($raw['videoPoster'] ?? null),
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function agenda(array $raw): array
    {
        $items = [];

        foreach ($this->list($raw['items'] ?? null) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $items[] = [
                'dia' => (int) ($item['dia'] ?? 0),
                'tema' => $this->string($item['tema'] ?? ''),
                'title' => $this->string($item['title'] ?? ''),
                'desc' => $this->string($item['desc'] ?? ''),
                'href' => $this->contentSanitizer->sanitizeUrl($this->string($item['href'] ?? '#')),
            ];
        }

        return [
            'eyebrow' => $this->string($raw['eyebrow'] ?? ''),
            'title' => $this->string($raw['title'] ?? ''),
            'body' => $this->string($raw['body'] ?? ''),
            'items' => $items,
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function onde(array $raw): array
    {
        $items = [];

        foreach ($this->list($raw['items'] ?? null) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $items[] = [
                'data' => $this->string($item['data'] ?? ''),
                'local' => $this->string($item['local'] ?? ''),
                'title' => $this->string($item['title'] ?? ''),
                'hora' => $this->string($item['hora'] ?? ''),
                'href' => $this->contentSanitizer->sanitizeUrl($this->string($item['href'] ?? '#')),
            ];
        }

        return [
            'eyebrow' => $this->string($raw['eyebrow'] ?? ''),
            'title' => $this->string($raw['title'] ?? ''),
            'titleAccent' => $this->string($raw['titleAccent'] ?? ''),
            'image' => $this->contentSanitizer->sanitizeUrl($this->string($raw['image'] ?? '/images/where-img.png')),
            'ctaLabel' => $this->string($raw['ctaLabel'] ?? ''),
            'ctaHref' => $this->contentSanitizer->sanitizeUrl($this->string($raw['ctaHref'] ?? '#')),
            'items' => $items,
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function fatos(array $raw): array
    {
        $items = [];

        foreach ($this->list($raw['items'] ?? null) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $items[] = [
                'icon' => $this->string($item['icon'] ?? 'dots'),
                'title' => $this->string($item['title'] ?? ''),
                'desc' => $this->string($item['desc'] ?? ''),
                'cta' => $this->string($item['cta'] ?? ''),
                'href' => $this->contentSanitizer->sanitizeUrl($this->string($item['href'] ?? '#')),
            ];
        }

        return [
            'eyebrow' => $this->string($raw['eyebrow'] ?? ''),
            'title' => $this->string($raw['title'] ?? ''),
            'titleAccent' => $this->string($raw['titleAccent'] ?? ''),
            'body' => $this->string($raw['body'] ?? ''),
            'items' => $items,
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function baixada(array $raw): array
    {
        $gallery = [];

        foreach ($this->list($raw['gallery'] ?? null) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $gallery[] = [
                'src' => $this->contentSanitizer->sanitizeUrl($this->string($item['src'] ?? '')),
                'alt' => $this->string($item['alt'] ?? ''),
            ];
        }

        return [
            'eyebrow' => $this->string($raw['eyebrow'] ?? ''),
            'title' => $this->string($raw['title'] ?? ''),
            'titleAccent' => $this->string($raw['titleAccent'] ?? ''),
            'body' => $this->string($raw['body'] ?? ''),
            'gallery' => $gallery,
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function capitulo(array $raw): array
    {
        $items = [];

        foreach ($this->list($raw['items'] ?? null) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $copy = is_array($item['copy'] ?? null) ? $item['copy'] : [];
            $desafio = $this->string($item['desafio'] ?? '');
            $proposta = $this->string($item['proposta'] ?? '');
            $estado = $this->string($item['estado'] ?? '');

            $items[] = [
                'icon' => $this->contentSanitizer->sanitizeUrl($this->string($item['icon'] ?? '')),
                'title' => $this->string($item['title'] ?? ''),
                'desafio' => $desafio,
                'proposta' => $proposta,
                'estado' => $estado,
                'copy' => [
                    'desafio' => $this->string($copy['desafio'] ?? $desafio),
                    'proposta' => $this->string($copy['proposta'] ?? $proposta),
                    'estado' => $this->string($copy['estado'] ?? $estado),
                    'body' => $this->listOfStrings($copy['body'] ?? []),
                ],
            ];
        }

        return [
            'eyebrow' => $this->string($raw['eyebrow'] ?? ''),
            'title' => $this->string($raw['title'] ?? ''),
            'items' => $items,
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function canal(array $raw): array
    {
        return [
            'eyebrow' => $this->string($raw['eyebrow'] ?? ''),
            'title' => $this->string($raw['title'] ?? ''),
            'body' => $this->listOfStrings($raw['body'] ?? []),
            'highlight' => $this->string($raw['highlight'] ?? ''),
            'primaryCtaLabel' => $this->string($raw['primaryCtaLabel'] ?? ''),
            'primaryCtaHref' => $this->contentSanitizer->sanitizeUrl($this->string($raw['primaryCtaHref'] ?? '#')),
            'secondaryCtaLabel' => $this->string($raw['secondaryCtaLabel'] ?? ''),
            'secondaryCtaHref' => $this->contentSanitizer->sanitizeUrl($this->string($raw['secondaryCtaHref'] ?? '#')),
            'caption' => $this->string($raw['caption'] ?? ''),
            'image' => $this->contentSanitizer->sanitizeUrl($this->string($raw['image'] ?? '/images/canal.png')),
            'imageAlt' => $this->string($raw['imageAlt'] ?? ''),
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function redes(array $raw): array
    {
        $links = [];

        foreach ($this->list($raw['links'] ?? null) as $link) {
            if (! is_array($link)) {
                continue;
            }

            $links[] = [
                'label' => $this->string($link['label'] ?? ''),
                'href' => $this->contentSanitizer->sanitizeUrl($this->string($link['href'] ?? '#')),
            ];
        }

        $videos = [];

        foreach ($this->list($raw['videos'] ?? null) as $video) {
            if (! is_array($video)) {
                continue;
            }

            $videos[] = [
                'src' => $this->nullableUrl($video['src'] ?? null),
                'poster' => $this->nullableUrl($video['poster'] ?? null),
            ];
        }

        return [
            'eyebrow' => $this->string($raw['eyebrow'] ?? ''),
            'title' => $this->string($raw['title'] ?? ''),
            'body' => $this->string($raw['body'] ?? ''),
            'links' => $links,
            'videos' => $videos,
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function movimento(array $raw): array
    {
        return [
            'eyebrow' => $this->string($raw['eyebrow'] ?? ''),
            'title' => $this->string($raw['title'] ?? ''),
            'body' => $this->string($raw['body'] ?? ''),
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function footer(array $raw): array
    {
        return [
            'name' => $this->string($raw['name'] ?? 'Lucas Mourão'),
            'role' => $this->string($raw['role'] ?? 'Deputado estadual'),
            'legal' => $this->string($raw['legal'] ?? ''),
            'privacyHref' => $this->contentSanitizer->sanitizeUrl($this->string($raw['privacyHref'] ?? '#')),
            'termsHref' => $this->contentSanitizer->sanitizeUrl($this->string($raw['termsHref'] ?? '#')),
        ];
    }

    /**
     * @return list<mixed>
     */
    private function list(mixed $value): array
    {
        return is_array($value) ? array_values($value) : [];
    }

    /**
     * @return list<string>
     */
    private function listOfStrings(mixed $value): array
    {
        $items = [];

        foreach ($this->list($value) as $item) {
            if (is_string($item) && trim($item) !== '') {
                $items[] = trim($item);
            }
        }

        return $items;
    }

    private function string(mixed $value): string
    {
        return is_string($value) ? trim($value) : '';
    }

    private function nullableUrl(mixed $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        return $this->contentSanitizer->sanitizeUrl(trim($value));
    }
}
