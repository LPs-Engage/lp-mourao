import { Head, usePage } from '@inertiajs/react';
import type { PageSeo } from '@/types/seo';

type SeoHeadProps = {
    seo: PageSeo;
};

export default function SeoHead({ seo }: SeoHeadProps) {
    const { seoDefaults } = usePage().props;
    const twitterHandle = seoDefaults?.twitterHandle?.trim() ?? '';

    return (
        <Head>
            <title>{seo.title}</title>
            <meta head-key="description" name="description" content={seo.description} />
            <meta head-key="robots" name="robots" content={seo.robots} />
            <link head-key="canonical" rel="canonical" href={seo.canonical} />

            <meta head-key="og:title" property="og:title" content={seo.ogTitle} />
            <meta head-key="og:description" property="og:description" content={seo.ogDescription} />
            <meta head-key="og:image" property="og:image" content={seo.ogImage} />
            <meta head-key="og:url" property="og:url" content={seo.ogUrl} />
            <meta head-key="og:type" property="og:type" content={seo.ogType} />
            {seoDefaults?.siteName && (
                <meta head-key="og:site_name" property="og:site_name" content={seoDefaults.siteName} />
            )}
            <meta
                head-key="og:locale"
                property="og:locale"
                content={seoDefaults?.locale || 'pt_BR'}
            />

            <meta head-key="twitter:card" name="twitter:card" content={seo.twitterCard} />
            <meta head-key="twitter:title" name="twitter:title" content={seo.ogTitle} />
            <meta head-key="twitter:description" name="twitter:description" content={seo.ogDescription} />
            <meta head-key="twitter:image" name="twitter:image" content={seo.ogImage} />
            {twitterHandle && (
                <meta head-key="twitter:site" name="twitter:site" content={twitterHandle} />
            )}

            {seo.jsonLd && (
                <script
                    head-key="json-ld"
                    type="application/ld+json"
                >
                    {JSON.stringify(seo.jsonLd)}
                </script>
            )}
        </Head>
    );
}
