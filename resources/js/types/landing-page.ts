import type { FaqBlock } from '@/types/faq';
import type { PageSeo } from '@/types/seo';

export type LandingPageTemplate = 'hero-only' | 'minimal-lead';

export type LandingPageSection = {
    title: string;
    body: string;
};

export type LandingPageContentSection = {
    title: string;
    body: string;
    slides: LandingPageSlide[];
    slideAutoPlayMs?: number;
};

export type LandingPageSlide = {
    image?: string | null;
    imageAlt?: string;
    eyebrow?: string;
    title?: string;
    body?: string;
};

export type LandingPageThankYou = {
    title: string;
    message: string;
    ctaLabel: string;
    ctaHref: string;
    secondaryCtaLabel?: string;
    secondaryCtaHref?: string;
};

export type LandingPageSeoConfig = {
    title: string;
    description: string;
    robots?: string;
    ogImage?: string | null;
    ogType?: string;
    schema?: string;
};

export type LandingPageConfig = {
    slug: string;
    active?: boolean;
    productName: string;
    template: LandingPageTemplate;
    theme: string;
    seo: LandingPageSeoConfig;
    thankYouSeo?: LandingPageSeoConfig;
    heroTitle: string;
    heroSubtitle: string;
    ctaLabel: string;
    ctaHref: string;
    sections: LandingPageSection[];
    contentSections: LandingPageContentSection[];
    slides: LandingPageSlide[];
    slideAutoPlayMs?: number;
    faq: FaqBlock | null;
    thankYou: LandingPageThankYou;
};

export type LandingPageShowProps = {
    landingPage: LandingPageConfig;
    seo: PageSeo;
};

export type LandingPageThankYouProps = {
    landingPage: Pick<
        LandingPageConfig,
        'slug' | 'productName' | 'template' | 'theme'
    >;
    thankYou: LandingPageThankYou;
    seo: PageSeo;
};
