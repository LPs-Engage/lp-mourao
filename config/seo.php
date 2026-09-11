<?php

return [

    'site_name' => env('SEO_SITE_NAME', env('APP_NAME', 'Lucas Mourão')),

    'title_suffix' => env('SEO_TITLE_SUFFIX', 'Lucas Mourão'),

    'base_url' => env('SEO_BASE_URL', env('APP_URL', 'http://localhost')),

    'locale' => env('SEO_LOCALE', 'pt_BR'),

    'default_og_image' => env('SEO_DEFAULT_OG_IMAGE', '/images/hero-backgroundV2.png'),

    'twitter_handle' => env('SEO_TWITTER_HANDLE', ''),

    'sitemap' => [
        'institutional_paths' => [
            '/' => 'home',
        ],
    ],

];
