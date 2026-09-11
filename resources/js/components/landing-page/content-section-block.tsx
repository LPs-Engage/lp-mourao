import SlideCarousel from '@/components/site/slide-carousel';
import TextReveal from '@/components/site/text-reveal';
import { cn } from '@/lib/utils';
import type { LandingPageContentSection } from '@/types/landing-page';

type LandingPageContentSectionBlockProps = {
    section: LandingPageContentSection;
    className?: string;
    containerClassName?: string;
};

export default function LandingPageContentSectionBlock({
    section,
    className,
    containerClassName,
}: LandingPageContentSectionBlockProps) {
    const hasSlides = section.slides.length > 0;
    const hasBody = Boolean(section.body?.trim());

    return (
        <section className={cn('ibnes-section', className)}>
            <div
                className={cn(
                    'ibnes-container',
                    hasSlides ? 'max-w-5xl' : 'max-w-3xl',
                    containerClassName,
                )}
            >
                <TextReveal as="h2" delay={0} className="text-2xl font-semibold text-ibnes-navy md:text-3xl">
                    {section.title}
                </TextReveal>
                {hasBody && (
                    <TextReveal as="p" delay={1} className="mt-4 text-base leading-relaxed text-ibnes-muted md:text-lg">
                        {section.body}
                    </TextReveal>
                )}
                {hasSlides && (
                    <div className={cn(hasBody ? 'mt-8' : 'mt-6')}>
                        <SlideCarousel
                            slides={section.slides}
                            autoPlayMs={section.slideAutoPlayMs ?? undefined}
                            variant="embedded"
                        />
                    </div>
                )}
            </div>
        </section>
    );
}
