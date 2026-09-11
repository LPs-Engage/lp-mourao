<?php

namespace App\Services\Content;

class PageContentSanitizer
{
    /**
     * @return list<array{title: string, body: string, slides: list<array{image: string|null, imageAlt: string, eyebrow: string, title: string, body: string}>, slideAutoPlayMs: int|null}>
     */
    public function sanitizeContentSections(mixed $rawSections): array
    {
        if (! is_array($rawSections)) {
            return [];
        }

        $sections = [];

        foreach ($rawSections as $section) {
            if (! is_array($section) || ! isset($section['title']) || ! is_string($section['title'])) {
                continue;
            }

            $title = trim($section['title']);
            $body = isset($section['body']) && is_string($section['body']) ? trim($section['body']) : '';
            $slides = $this->sanitizeSlides($section['slides'] ?? []);

            if ($title === '' || ($body === '' && $slides === [])) {
                continue;
            }

            $sections[] = [
                'title' => $title,
                'body' => $body,
                'slides' => $slides,
                'slideAutoPlayMs' => $this->sanitizeSlideAutoPlayMs($section['slideAutoPlayMs'] ?? null),
            ];
        }

        return $sections;
    }

    public function sanitizeSlideAutoPlayMs(mixed $value): ?int
    {
        if (! is_numeric($value)) {
            return null;
        }

        $milliseconds = (int) $value;

        if ($milliseconds < 2000 || $milliseconds > 30000) {
            return null;
        }

        return $milliseconds;
    }

    /**
     * @return list<array{image: string|null, imageAlt: string, eyebrow: string, title: string, body: string}>
     */
    public function sanitizeSlides(mixed $rawSlides): array
    {
        if (! is_array($rawSlides)) {
            return [];
        }

        $slides = [];

        foreach ($rawSlides as $slide) {
            if (! is_array($slide)) {
                continue;
            }

            $image = isset($slide['image']) && is_string($slide['image']) && trim($slide['image']) !== ''
                ? $this->sanitizeUrl(trim($slide['image']))
                : null;
            $title = isset($slide['title']) && is_string($slide['title']) ? trim($slide['title']) : '';
            $body = isset($slide['body']) && is_string($slide['body']) ? trim($slide['body']) : '';
            $eyebrow = isset($slide['eyebrow']) && is_string($slide['eyebrow']) ? trim($slide['eyebrow']) : '';

            if ($image === null && $title === '' && $body === '' && $eyebrow === '') {
                continue;
            }

            $slides[] = [
                'image' => $image,
                'imageAlt' => isset($slide['imageAlt']) && is_string($slide['imageAlt']) ? trim($slide['imageAlt']) : '',
                'eyebrow' => $eyebrow,
                'title' => $title,
                'body' => $body,
            ];
        }

        return $slides;
    }

    public function sanitizeUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '/';
        }

        if (preg_match('/^(javascript|data):/i', $url)) {
            return '/';
        }

        if (str_starts_with($url, '/')) {
            return $url;
        }

        if (preg_match('/^(https?:\/\/|mailto:|tel:)/i', $url)) {
            return $url;
        }

        return '/';
    }

    /**
     * @return list<array{question: string, answer: string}>
     */
    public function sanitizeFaqItems(mixed $rawItems): array
    {
        if (! is_array($rawItems)) {
            return [];
        }

        $items = [];

        foreach ($rawItems as $item) {
            if (! is_array($item) || ! isset($item['question'], $item['answer'])) {
                continue;
            }

            $question = is_string($item['question']) ? trim($item['question']) : '';
            $answer = is_string($item['answer']) ? trim($item['answer']) : '';

            if ($question === '' || $answer === '') {
                continue;
            }

            $items[] = [
                'question' => $question,
                'answer' => $answer,
            ];
        }

        return $items;
    }
}
