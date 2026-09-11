<?php

namespace App\Support;

/**
 * Constraints for clean LP URLs (/{slug}, /{slug}/obrigado, /{slug}/lead).
 *
 * Static site/auth routes are registered first; this blocklist is a second
 * line of defense so catch-all routes never swallow reserved first segments
 * (e.g. Fortify paths registered after web.php).
 */
final class LandingPageRoutes
{
    /**
     * First-path segments that must never be treated as LP slugs.
     *
     * @var list<string>
     */
    public const RESERVED_SLUGS = [
        'obrigado',
        'cadastro',
        'dashboard',
        'login',
        'register',
        'forgot-password',
        'reset-password',
        'email',
        'settings',
        'up',
        'storage',
        'two-factor-challenge',
        'user',
        'passkeys',
        'lp',
        'sitemap.xml',
    ];

    /**
     * Regex constraint for {slug} route parameters (Laravel where() pattern).
     */
    public static function slugConstraint(): string
    {
        $reserved = implode('|', array_map(
            static fn (string $slug): string => preg_quote($slug, '/'),
            self::RESERVED_SLUGS,
        ));

        return '(?!('.$reserved.')$)[a-z0-9-]+';
    }
}
