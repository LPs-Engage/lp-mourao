import BlocosMarquee from '@/components/site/blocos-marquee';
import Reveal from '@/components/site/reveal';
import type { HomeLucasContent } from '@/types/home';

export default function HomeLucas({ content }: { content: HomeLucasContent }) {
    return (
        <section id="lucas" className="bg-white">
            <div className="mourao-container mourao-section">
                <div className="flex flex-col items-center gap-2.5 text-center">
                    <Reveal>
                        <p className="mourao-eyebrow text-mourao-blue">{content.eyebrow}</p>
                    </Reveal>
                    <Reveal>
                        <h2 className="mb-5 max-w-[900px] uppercase">
                            {content.title}
                            <br />
                            <span className="text-mourao-orange">{content.titleAccent}</span>
                        </h2>
                    </Reveal>
                    <div className="mt-8 grid w-full grid-cols-1 gap-4 text-center md:grid-cols-2 md:gap-5 lg:grid-cols-5">
                        {content.items.map((item) => (
                            <Reveal key={item.title}>
                                <article className="flex flex-col items-center gap-4 px-5 py-8">
                                    <img
                                        src={item.icon}
                                        alt={item.iconAlt}
                                        className="h-auto w-[72px]"
                                    />
                                    <h3 className="uppercase">{item.title}</h3>
                                    <p>{item.body}</p>
                                </article>
                            </Reveal>
                        ))}
                    </div>
                </div>
            </div>
            <BlocosMarquee />
        </section>
    );
}
