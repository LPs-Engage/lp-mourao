import BlocosMarquee from '@/components/site/blocos-marquee';
import Reveal from '@/components/site/reveal';
import type { HomeCanalContent } from '@/types/home';

export default function HomeCanal({ content }: { content: HomeCanalContent }) {
    return (
        <section id="canal" className="mourao-bg-orange">
            <div className="mourao-container mourao-section">
                <article
                    aria-label={content.imageAlt}
                    className="relative overflow-hidden rounded-[28px] bg-mourao-navy lg:rounded-[36px]"
                >
                    <img
                        src={content.image}
                        alt={content.imageAlt}
                        className="block h-auto w-full"
                    />
                    <div className="relative z-10 flex flex-col justify-center bg-mourao-navy px-6 py-10 md:px-10 lg:absolute lg:inset-y-[6%] lg:left-[6%] lg:w-[40%] lg:bg-transparent lg:px-8 lg:py-6 xl:px-10">
                        <Reveal>
                            <p className="mourao-eyebrow text-mourao-orange">
                                {content.eyebrow}
                            </p>
                            <h2 className="mt-4 max-w-[14ch] uppercase leading-[1.05] text-white">
                                {content.title}
                            </h2>
                            {content.body.map((paragraph) => (
                                <p
                                    key={paragraph}
                                    className="mt-4 max-w-[38ch] text-[clamp(14px,2vw,18px)] leading-relaxed text-white/90"
                                >
                                    {paragraph}
                                </p>
                            ))}
                            {content.highlight ? (
                                <p className="mt-5 max-w-[18ch] text-[clamp(22px,3vw,32px)] font-extrabold leading-[1.1] whitespace-pre-line uppercase text-mourao-orange">
                                    {content.highlight}
                                </p>
                            ) : null}
                            <div className="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                                <a
                                    href={content.primaryCtaHref}
                                    className="inline-flex items-center justify-center rounded-lg bg-mourao-orange px-6 py-3.5 text-xs font-extrabold tracking-[0.04em] uppercase sm:text-sm"
                                >
                                    {content.primaryCtaLabel}
                                </a>
                                <a
                                    href={content.secondaryCtaHref}
                                    className="inline-flex items-center justify-center rounded-lg border border-white px-6 py-3.5 text-xs font-extrabold tracking-[0.04em] uppercase sm:text-sm"
                                >
                                    {content.secondaryCtaLabel}
                                </a>
                            </div>
                            <p className="mt-6 text-[11px] font-extrabold tracking-[0.08em] uppercase text-white/90 md:text-xs">
                                {content.caption}
                            </p>
                        </Reveal>
                    </div>
                </article>
            </div>
            <BlocosMarquee />
        </section>
    );
}
