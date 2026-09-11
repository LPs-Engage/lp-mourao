<?php

namespace App\Services\Content;

use Illuminate\Support\Facades\File;

class FaqResolver
{
    public function __construct(private PageContentSanitizer $contentSanitizer) {}

    /**
     * @return array{title: string, description: string, eyebrow: string, items: list<array{question: string, answer: string}>}|null
     */
    public function resolve(mixed $config): ?array
    {
        if (! is_array($config)) {
            return null;
        }

        $items = $this->contentSanitizer->sanitizeFaqItems($config['items'] ?? []);

        if ($items === [] && isset($config['set']) && is_string($config['set'])) {
            $items = $this->itemsForSet(trim($config['set']));
        }

        if ($items === []) {
            return null;
        }

        return [
            'title' => isset($config['title']) && is_string($config['title']) ? trim($config['title']) : 'Perguntas frequentes',
            'description' => isset($config['description']) && is_string($config['description']) ? trim($config['description']) : '',
            'eyebrow' => isset($config['eyebrow']) && is_string($config['eyebrow']) ? trim($config['eyebrow']) : 'FAQ',
            'items' => $items,
        ];
    }

    /**
     * @return list<array{question: string, answer: string}>
     */
    private function itemsForSet(string $setKey): array
    {
        if ($setKey === '') {
            return [];
        }

        $path = resource_path('data/faq.json');

        if (! File::exists($path)) {
            return [];
        }

        $decoded = json_decode(File::get($path), true);

        if (! is_array($decoded) || ! isset($decoded['sets'][$setKey])) {
            return [];
        }

        return $this->contentSanitizer->sanitizeFaqItems($decoded['sets'][$setKey]);
    }
}
