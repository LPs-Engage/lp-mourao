import Reveal from '@/components/site/reveal';
import { cn } from '@/lib/utils';
import type { HomeTrabalhoContent, ImageAsset } from '@/types/home';

function PhotoCollage({ images }: { images: ImageAsset[] }) {
    if (images.length === 1) {
        return (
            <img
                src={images[0].src}
                alt={images[0].alt}
                className="mx-auto block w-full max-w-[560px] drop-shadow-[0_12px_22px_rgba(0,0,0,0.28)]"
            />
        );
    }

    return (
        <div className="relative mx-auto aspect-[6/5] w-full max-w-[520px]">
            <img
                src={images[0].src}
                alt={images[0].alt}
                className="absolute top-0 left-0 z-10 w-[72%] drop-shadow-[0_12px_22px_rgba(0,0,0,0.28)]"
            />
            <img
                src={images[1].src}
                alt={images[1].alt}
                className="absolute right-0 bottom-0 z-20 w-[72%] drop-shadow-[0_12px_22px_rgba(0,0,0,0.28)]"
            />
        </div>
    );
}

export default function HomeTrabalho({ content }: { content: HomeTrabalhoContent }) {
    return (
        <section id="trabalho" className="mourao-bg-orange text-white">
            <div className="mourao-container mourao-section">
                <Reveal>
                    <p className="mourao-eyebrow text-center font-bold text-white">{content.eyebrow}</p>
                </Reveal>
                <Reveal>
                    <h2 className="mx-auto mt-3 max-w-[18ch] text-center text-[clamp(22px,5vw,42px)] uppercase leading-[1.08] text-white">
                        {content.title}
                    </h2>
                </Reveal>

                <div className="mt-10 flex flex-col gap-8 sm:mt-12 sm:gap-12 lg:mt-16 lg:gap-16">
                    {content.items.map((item, index) => {
                        const photosLeft = index % 2 === 1;

                        return (
                            <article
                                key={item.title}
                                className={cn(
                                    'grid items-center gap-3 sm:gap-6 lg:gap-12',
                                    photosLeft
                                        ? 'grid-cols-[1.15fr_0.85fr]'
                                        : 'grid-cols-[0.85fr_1.15fr]',
                                )}
                            >
                                <Reveal className={photosLeft ? 'order-2' : 'order-1'}>
                                    <div>
                                        <h3 className="border-l-[5px] border-mourao-blue py-0.5 pl-3 text-[clamp(20px,4.2vw,42px)] font-extrabold uppercase leading-[1.05] whitespace-pre-line sm:border-l-8 sm:pl-4">
                                            {item.title}
                                        </h3>
                                        {item.body ? (
                                            <p className="mt-1.5 pl-3.5 text-[clamp(11px,2vw,18px)] leading-snug text-white/95 sm:mt-2.5 sm:pl-6">
                                                {item.body}
                                            </p>
                                        ) : null}
                                    </div>
                                </Reveal>
                                <Reveal
                                    from={photosLeft ? 'left' : 'right'}
                                    className={photosLeft ? 'order-1 min-w-0' : 'order-2 min-w-0'}
                                >
                                    <PhotoCollage images={item.images} />
                                </Reveal>
                            </article>
                        );
                    })}
                </div>
            </div>
        </section>
    );
}
