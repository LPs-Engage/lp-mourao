<?php

namespace App\Services\Seo;

class SitemapGenerator
{
    public function __construct(private SeoResolver $seoResolver) {}

    public function toXml(): string
    {
        $urls = $this->seoResolver->sitemapUrls();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= '  <url>'."\n";
            $xml .= '    <loc>'.htmlspecialchars($url['loc'], ENT_XML1 | ENT_COMPAT, 'UTF-8').'</loc>'."\n";

            if (isset($url['lastmod'])) {
                $xml .= '    <lastmod>'.htmlspecialchars($url['lastmod'], ENT_XML1 | ENT_COMPAT, 'UTF-8').'</lastmod>'."\n";
            }

            $xml .= '  </url>'."\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
