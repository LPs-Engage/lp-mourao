import { useState } from 'react';
import BlocosMarquee from '@/components/site/blocos-marquee';
import Reveal from '@/components/site/reveal';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import type { HomeCapituloContent, HomeCapituloItem } from '@/types/home';

function CapituloBlock({ label, text }: { label: string; text: string }) {
    if (!text) {
        return null;
    }

    return (
        <div className="space-y-1">
            <p className="text-xs font-extrabold tracking-[0.06em] uppercase text-mourao-orange">{label}</p>
            <p className="whitespace-pre-line">{text}</p>
        </div>
    );
}

export default function HomeCapitulo({ content }: { content: HomeCapituloContent }) {
    const [open, setOpen] = useState(false);
    const [selected, setSelected] = useState<HomeCapituloItem | null>(null);

    const openItem = (item: HomeCapituloItem) => {
        setSelected(item);
        setOpen(true);
    };

    return (
        <section id="capitulo" className="bg-mourao-gray">
            <div className="mourao-container mourao-section">
                <div className="flex flex-col items-center gap-4 text-center">
                    <Reveal>
                        <p className="mourao-eyebrow text-mourao-blue">{content.eyebrow}</p>
                    </Reveal>
                    <Reveal>
                        <h2 className="uppercase">{content.title}</h2>
                    </Reveal>

                    <div className="mt-8 grid w-full grid-cols-1 gap-4 text-left md:grid-cols-2 lg:grid-cols-3">
                        {content.items.map((item) => (
                            <Reveal key={item.title}>
                                <article>
                                    <button
                                        type="button"
                                        onClick={() => openItem(item)}
                                        className="flex h-full w-full flex-col gap-2 rounded-2xl bg-white p-6 text-left transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-mourao-yellow"
                                    >
                                        <img src={item.icon} alt="" className="mb-2 h-12 w-12 object-contain" />
                                        <h3 className="uppercase">{item.title}</h3>
                                        <CapituloBlock label="O desafio" text={item.desafio} />
                                        <CapituloBlock label="A proposta" text={item.proposta} />
                                        <CapituloBlock label="Como o Estado pode atuar" text={item.estado} />
                                        <span className="mt-3 text-xs font-extrabold uppercase text-mourao-navy">
                                            Saiba mais ↗
                                        </span>
                                    </button>
                                </article>
                            </Reveal>
                        ))}
                    </div>
                </div>
            </div>
            <BlocosMarquee />

            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent
                    overlayClassName="bg-mourao-navy/90"
                    className="max-h-[min(90vh,760px)] overflow-y-auto rounded-2xl border-0 bg-white text-mourao-navy shadow-2xl sm:max-w-2xl"
                >
                    {selected ? (
                        <>
                            <DialogHeader className="pr-8 text-left">
                                <div className="mb-3 flex items-center gap-3">
                                    <img src={selected.icon} alt="" className="h-12 w-12 object-contain" />
                                    <DialogTitle className="text-[clamp(22px,4vw,32px)] font-extrabold uppercase text-mourao-navy">
                                        {selected.title}
                                    </DialogTitle>
                                </div>
                                <DialogDescription className="sr-only">
                                    Texto completo sobre {selected.title} na Baixada.
                                </DialogDescription>
                            </DialogHeader>
                            <div className="space-y-5 text-left text-mourao-navy">
                                {selected.copy.body.map((paragraph) => (
                                    <p key={paragraph} className="whitespace-pre-line">
                                        {paragraph}
                                    </p>
                                ))}
                                <CapituloBlock label="O desafio" text={selected.copy.desafio} />
                                <CapituloBlock label="A proposta" text={selected.copy.proposta} />
                                <CapituloBlock label="Como o Estado pode atuar" text={selected.copy.estado} />
                            </div>
                        </>
                    ) : null}
                </DialogContent>
            </Dialog>
        </section>
    );
}
