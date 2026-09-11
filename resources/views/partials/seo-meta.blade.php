@php
    /** @var array<string, mixed>|null $seo */
    $seo = is_array($page['props']['seo'] ?? null) ? $page['props']['seo'] : null;
    /** @var array<string, mixed>|null $seoDefaults */
    $seoDefaults = is_array($page['props']['seoDefaults'] ?? null) ? $page['props']['seoDefaults'] : null;

    $fallbackTitle = (string) ($seoDefaults['titleSuffix'] ?? config('seo.title_suffix', config('app.name', 'Lucas Mourão')));
    $siteName = (string) ($seoDefaults['siteName'] ?? config('seo.site_name', $fallbackTitle));
    if ($siteName === '' || str_contains($siteName, '${')) {
        $siteName = $fallbackTitle;
    }
    $locale = (string) ($seoDefaults['locale'] ?? config('seo.locale', 'pt_BR'));
    $twitterHandle = trim((string) ($seoDefaults['twitterHandle'] ?? ''));

    $title = is_array($seo) ? (string) ($seo['title'] ?? $fallbackTitle) : $fallbackTitle;
    $description = is_array($seo) ? (string) ($seo['description'] ?? '') : '';
    $robots = is_array($seo) ? (string) ($seo['robots'] ?? 'index,follow') : 'index,follow';
    $canonical = is_array($seo) ? (string) ($seo['canonical'] ?? '') : '';
    $ogTitle = is_array($seo) ? (string) ($seo['ogTitle'] ?? $title) : $title;
    $ogDescription = is_array($seo) ? (string) ($seo['ogDescription'] ?? $description) : $description;
    $ogImage = is_array($seo) ? (string) ($seo['ogImage'] ?? '') : '';
    $ogUrl = is_array($seo) ? (string) ($seo['ogUrl'] ?? $canonical) : '';
    $ogType = is_array($seo) ? (string) ($seo['ogType'] ?? 'website') : 'website';
    $twitterCard = is_array($seo) ? (string) ($seo['twitterCard'] ?? 'summary_large_image') : 'summary_large_image';
    $jsonLd = is_array($seo) && is_array($seo['jsonLd'] ?? null) ? $seo['jsonLd'] : null;
@endphp

<title inertia>{{ $title }}</title>

@if($description !== '')
    <meta head-key="description" name="description" content="{{ $description }}">
@endif

<meta head-key="robots" name="robots" content="{{ $robots }}">

@if($canonical !== '')
    <link head-key="canonical" rel="canonical" href="{{ $canonical }}">
@endif

<meta head-key="og:title" property="og:title" content="{{ $ogTitle }}">
@if($ogDescription !== '')
    <meta head-key="og:description" property="og:description" content="{{ $ogDescription }}">
@endif
@if($ogImage !== '')
    <meta head-key="og:image" property="og:image" content="{{ $ogImage }}">
@endif
@if($ogUrl !== '')
    <meta head-key="og:url" property="og:url" content="{{ $ogUrl }}">
@endif
<meta head-key="og:type" property="og:type" content="{{ $ogType }}">
<meta head-key="og:site_name" property="og:site_name" content="{{ $siteName }}">
<meta head-key="og:locale" property="og:locale" content="{{ $locale }}">

<meta head-key="twitter:card" name="twitter:card" content="{{ $twitterCard }}">
<meta head-key="twitter:title" name="twitter:title" content="{{ $ogTitle }}">
@if($ogDescription !== '')
    <meta head-key="twitter:description" name="twitter:description" content="{{ $ogDescription }}">
@endif
@if($ogImage !== '')
    <meta head-key="twitter:image" name="twitter:image" content="{{ $ogImage }}">
@endif
@if($twitterHandle !== '')
    <meta head-key="twitter:site" name="twitter:site" content="{{ $twitterHandle }}">
@endif

@if($jsonLd)
    <script head-key="json-ld" type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
@endif
