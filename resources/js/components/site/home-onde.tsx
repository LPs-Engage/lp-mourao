import BlocosMarquee from '@/components/site/blocos-marquee';
import Reveal from '@/components/site/reveal';
import type { HomeOndeContent } from '@/types/home';

export default function HomeOnde({ content }: { content: HomeOndeContent }) {
    return (
        <section id="onde" className="bg-white">
            <div className="mourao-container mourao-section">
                <div className="flex flex-col items-center gap-4 text-center">
                    <Reveal>
                        <p className="mourao-eyebrow text-mourao-blue">{content.eyebrow}</p>
                    </Reveal>
                    <Reveal>
                        <h2 className="uppercase">
                            {content.title}{' '}
                            <span className="text-mourao-orange">{content.titleAccent}</span>
                        </h2>
                    </Reveal>

                    <div className="mt-6 grid w-full grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                        {content.items.map((item) => (
                            <Reveal key={`${item.data}-${item.local}-${item.title}`}>
                                <article className="flex flex-col overflow-hidden rounded-2xl bg-mourao-gray text-left">
                                    <img
                                        src={content.image}
                                        alt="Lucas Mourão"
                                        className="h-40 w-full object-cover"
                                    />
                                    <div className="flex flex-col gap-1 p-5">
                                        <p className="text-sm font-extrabold uppercase text-mourao-orange">
                                            {item.data}
                                        </p>
                                        <h3 className="uppercase">{item.local}</h3>
                                        <p>{item.title}</p>
                                        <p>às {item.hora}</p>
                                        <a
                                            href={item.href}
                                            className="mt-3 text-xs font-extrabold uppercase"
                                        >
                                            Ver detalhes ↗
                                        </a>
                                    </div>
                                </article>
                            </Reveal>
                        ))}
                    </div>

                    <a href={content.ctaHref} className="mourao-button mt-6 max-w-xs uppercase">
                        {content.ctaLabel}
                    </a>
                </div>
            </div>
            <BlocosMarquee />
        </section>
    );
}
