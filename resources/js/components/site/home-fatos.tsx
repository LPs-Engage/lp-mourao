import BlocosMarquee from '@/components/site/blocos-marquee';
import Reveal from '@/components/site/reveal';
import type { HomeFatosContent } from '@/types/home';

const ICONS: Record<string, string> = {
    dots: '•••',
    doc: '▣',
    lapis: '✎',
};

export default function HomeFatos({ content }: { content: HomeFatosContent }) {
    return (
        <section id="fatos" className="bg-white">
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
                    <Reveal>
                        <p>{content.body}</p>
                    </Reveal>

                    <div className="mt-8 grid w-full grid-cols-1 gap-4 md:grid-cols-3">
                        {content.items.map((item) => (
                            <Reveal key={item.title}>
                                <article className="flex flex-col items-center gap-3 rounded-2xl bg-mourao-gray px-6 py-8">
                                    <span
                                        className="text-2xl text-mourao-orange"
                                        aria-hidden="true"
                                    >
                                        {ICONS[item.icon] ?? '•'}
                                    </span>
                                    <h3 className="uppercase">{item.title}</h3>
                                    <p>{item.desc}</p>
                                    <a href={item.href} className="mt-2 text-xs font-extrabold uppercase">
                                        {item.cta} ↗
                                    </a>
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
