import SeoHead from '@/components/seo/seo-head';
import HeroOnlyTemplate from '@/pages/landing-pages/templates/hero-only-template';
import MinimalLeadTemplate from '@/pages/landing-pages/templates/minimal-lead-template';
import type { LandingPageShowProps } from '@/types/landing-page';

export default function LandingPageShow({
    landingPage,
    seo,
}: LandingPageShowProps) {
    return (
        <>
            <SeoHead seo={seo} />

            {landingPage.template === 'hero-only' && (
                <HeroOnlyTemplate landingPage={landingPage} />
            )}
            {landingPage.template === 'minimal-lead' && (
                <MinimalLeadTemplate landingPage={landingPage} />
            )}
        </>
    );
}
