import BlocosMarquee from '@/components/site/blocos-marquee';
import Reveal from '@/components/site/reveal';
import VideoPlayer from '@/components/site/video-player';
import type { HomeRedesContent } from '@/types/home';

export default function HomeRedes({ content }: { content: HomeRedesContent }) {
    return (
        <section id="redes" className="mourao-bg-orange text-white">
            <div className="mourao-container mourao-section pb-8">
                <div className="flex flex-col items-center gap-4 text-center">
                    <Reveal>
                        <p className="mourao-eyebrow font-extrabold text-white">{content.eyebrow}</p>
                    </Reveal>
                    <Reveal>
                        <h2 className="max-w-[780px] uppercase text-white">{content.title}</h2>
                    </Reveal>
                    <Reveal>
                        <p className="max-w-[560px] text-[clamp(14px,2vw,18px)] leading-snug">
                            {content.body}
                        </p>
                    </Reveal>
                    <div className="mt-2.5 flex flex-wrap justify-center gap-3">
                        {content.links.map((link) => (
                            <a
                                key={link.label}
                                href={link.href}
                                className="rounded-[40px] bg-white px-6 py-3 text-[clamp(11px,1.6vw,13px)] font-extrabold tracking-[0.06em] uppercase text-mourao-navy transition hover:-translate-y-0.5"
                            >
                                {link.label}
                            </a>
                        ))}
                    </div>
                </div>
            </div>

            <div className="flex gap-3 overflow-x-auto px-[clamp(16px,4vw,32px)] pb-[clamp(40px,8vw,80px)] lg:mx-auto lg:grid lg:max-w-[1440px] lg:grid-cols-5 lg:overflow-hidden">
                {content.videos.map((video, index) => (
                    <VideoPlayer
                        key={`${video.src ?? 'empty'}-${index}`}
                        src={video.src}
                        poster={video.poster}
                        className="aspect-9/16 min-w-[58%] rounded-[20px] lg:min-w-0 lg:rounded-3xl"
                    />
                ))}
            </div>
            <BlocosMarquee />
        </section>
    );
}
