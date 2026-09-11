import BlocosMarquee from '@/components/site/blocos-marquee';
import type { HomeHeroContent } from '@/types/home';

export default function HomeHero({ content }: { content: HomeHeroContent }) {
    return (
        <section id="hero" className="relative overflow-hidden leading-none">
            <h1 className="sr-only">{content.title}</h1>
            <div className="w-full leading-none">
                <picture>
                    <source media="(min-width: 768px)" srcSet={content.image} />
                    <img
                        src={content.imageMobile}
                        alt={content.imageAlt}
                        className="block h-auto w-full motion-safe:animate-mourao-fade-up"
                    />
                </picture>
            </div>
            <BlocosMarquee alt={content.marqueeAlt} />
        </section>
    );
}
