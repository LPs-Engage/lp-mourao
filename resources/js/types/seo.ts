export type RobotsDirective = 'index,follow' | 'noindex,nofollow' | 'noindex,follow';

export type OgType = 'website' | 'article';

export type SeoSchemaType = 'Organization' | 'Course' | 'Event' | 'WebPage';

export type PageSeo = {
    title: string;
    description: string;
    canonical: string;
    robots: RobotsDirective;
    ogTitle: string;
    ogDescription: string;
    ogImage: string;
    ogUrl: string;
    ogType: OgType;
    twitterCard: 'summary' | 'summary_large_image';
    jsonLd?: Record<string, unknown>;
};

export type SeoDefaults = {
    siteName: string;
    titleSuffix: string;
    baseUrl: string;
    defaultOgImage: string;
    twitterHandle: string;
    locale: string;
};
