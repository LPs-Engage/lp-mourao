import Reveal from '@/components/site/reveal';
import type { HomeBaixadaContent } from '@/types/home';

export default function HomeBaixada({ content }: { content: HomeBaixadaContent }) {
    return (
        <section id="baixada" className="relative overflow-hidden bg-white">
            <div className="mourao-container mourao-section pb-8">
                <div className="flex flex-col items-center gap-4 text-center">
                    <Reveal>
                        <p className="mourao-eyebrow text-mourao-blue">{content.eyebrow}</p>
                    </Reveal>
                    <Reveal>
                        <h2 className="max-w-[920px] uppercase">
                            {content.title}
                            <br />
                            <span className="text-mourao-orange">{content.titleAccent}</span>
                        </h2>
                    </Reveal>
                    <Reveal>
                        <p className="max-w-[640px]">{content.body}</p>
                    </Reveal>
                </div>
            </div>

            <Reveal from="left" className="pointer-events-none absolute top-24 left-0 hidden w-28 lg:block">
                <img src="/images/bike.png" alt="" aria-hidden="true" />
            </Reveal>
            <Reveal from="right" className="pointer-events-none absolute top-32 right-0 hidden w-24 lg:block">
                <img src="/images/ancor.png" alt="" aria-hidden="true" />
            </Reveal>

            <Reveal>
                <div className="flex gap-3 overflow-x-auto px-[clamp(16px,4vw,32px)] pb-[clamp(40px,8vw,80px)] lg:mx-auto lg:grid lg:max-w-[1440px] lg:grid-cols-5 lg:overflow-hidden">
                    {content.gallery.map((item) => (
                        <figure
                            key={item.src}
                            className="m-0 min-w-[70%] overflow-hidden rounded-2xl lg:min-w-0"
                        >
                            <img
                                src={item.src}
                                alt={item.alt}
                                className="h-full w-full object-cover transition-transform duration-400 hover:scale-105 motion-reduce:transform-none"
                            />
                        </figure>
                    ))}
                </div>
            </Reveal>
        </section>
    );
}
