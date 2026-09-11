import type { LandingPageConfig } from '@/types/landing-page';

type HeroOnlyTemplateProps = {
    landingPage: LandingPageConfig;
};

export default function HeroOnlyTemplate({ landingPage }: HeroOnlyTemplateProps) {
    return (
        <section className="relative flex min-h-dvh overflow-hidden bg-mourao-navy text-white">
            <div className="mourao-container relative flex flex-1 items-center py-14 md:py-20">
                <div className="max-w-4xl">
                    <p className="mourao-eyebrow text-mourao-orange">{landingPage.productName}</p>
                    <h1 className="mt-6 max-w-4xl text-white">{landingPage.heroTitle}</h1>
                    <p className="mt-7 max-w-2xl text-base leading-relaxed text-white/80 md:text-xl">
                        {landingPage.heroSubtitle}
                    </p>
                    <div className="mt-10">
                        <a
                            href={landingPage.ctaHref}
                            className="mourao-button max-w-xs uppercase"
                        >
                            {landingPage.ctaLabel}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    );
}
