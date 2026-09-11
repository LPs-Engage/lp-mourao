import SeoHead from '@/components/seo/seo-head';
import type { LandingPageThankYouProps } from '@/types/landing-page';

export default function LandingPageThankYou({
    landingPage,
    thankYou,
    seo,
}: LandingPageThankYouProps) {
    return (
        <>
            <SeoHead seo={seo} />

            <section className="mourao-section bg-white">
                <div className="mourao-container max-w-2xl text-center">
                    <p className="mourao-eyebrow mb-3 text-mourao-orange">
                        {landingPage.productName}
                    </p>
                    <h1 className="text-mourao-navy">{thankYou.title}</h1>
                    <p className="mt-6 text-base leading-relaxed md:text-lg">{thankYou.message}</p>
                    <div className="mt-10">
                        <a href={thankYou.ctaHref} className="mourao-button max-w-xs uppercase">
                            {thankYou.ctaLabel}
                        </a>
                    </div>
                </div>
            </section>
        </>
    );
}
