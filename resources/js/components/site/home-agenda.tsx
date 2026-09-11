import { useState } from 'react';
import BlocosMarquee from '@/components/site/blocos-marquee';
import Reveal from '@/components/site/reveal';
import type { HomeAgendaContent } from '@/types/home';

export default function HomeAgenda({ content }: { content: HomeAgendaContent }) {
    const [flipped, setFlipped] = useState<number | null>(null);

    return (
        <section id="agenda" className="mourao-bg-orange text-white">
            <div className="mourao-container mourao-section">
                <div className="flex flex-col items-center gap-4 text-center">
                    <Reveal>
                        <p className="mourao-eyebrow font-bold text-white">{content.eyebrow}</p>
                    </Reveal>
                    <Reveal>
                        <h2 className="uppercase text-white">{content.title}</h2>
                    </Reveal>
                    <Reveal>
                        <p className="mb-2.5 max-w-[560px] text-[clamp(14px,2vw,18px)] leading-snug text-white">
                            {content.body}
                        </p>
                    </Reveal>

                    <div className="mt-5 grid w-full grid-cols-2 gap-3 md:grid-cols-3 md:gap-4 lg:grid-cols-5">
                        {content.items.map((item) => {
                            const isFlipped = flipped === item.dia;

                            return (
                                <article
                                    key={item.dia}
                                    className="aspect-square cursor-pointer [perspective:1000px]"
                                    onClick={() => setFlipped(isFlipped ? null : item.dia)}
                                    onKeyDown={(event) => {
                                        if (event.key === 'Enter' || event.key === ' ') {
                                            event.preventDefault();
                                            setFlipped(isFlipped ? null : item.dia);
                                        }
                                    }}
                                    role="button"
                                    tabIndex={0}
                                    aria-pressed={isFlipped}
                                >
                                    <div
                                        className={`relative h-full w-full transition-transform duration-600 [transform-style:preserve-3d] motion-reduce:transition-none ${
                                            isFlipped ? '[transform:rotateY(180deg)]' : ''
                                        }`}
                                    >
                                        <p className="absolute inset-0 m-0 flex items-center justify-center rounded-lg border-2 border-mourao-orange bg-white text-[clamp(28px,5vw,42px)] font-extrabold text-mourao-orange [backface-visibility:hidden]">
                                            {String(item.dia).padStart(2, '0')}
                                        </p>
                                        <div className="absolute inset-0 flex flex-col items-start gap-2 rounded-lg bg-mourao-navy p-4 text-left text-white [backface-visibility:hidden] [transform:rotateY(180deg)] lg:p-5">
                                            <p className="flex items-center gap-2 text-[clamp(10px,1.6vw,12px)] font-extrabold tracking-[0.06em] uppercase">
                                                <span className="inline-block size-2 shrink-0 rounded-full bg-mourao-green" />
                                                {item.tema}
                                            </p>
                                            <h3 className="text-[clamp(14px,2vw,18px)] font-extrabold leading-tight uppercase text-white">
                                                {item.title}
                                            </h3>
                                            <p className="text-[clamp(11px,1.6vw,13px)] leading-snug font-normal">
                                                {item.desc}
                                            </p>
                                            <a
                                                href={item.href}
                                                className="mt-auto text-[clamp(10px,1.6vw,12px)] font-extrabold tracking-[0.06em] uppercase"
                                                onClick={(event) => event.stopPropagation()}
                                            >
                                                Ver detalhes ↗
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            );
                        })}
                    </div>
                </div>
            </div>
            <BlocosMarquee />
        </section>
    );
}
