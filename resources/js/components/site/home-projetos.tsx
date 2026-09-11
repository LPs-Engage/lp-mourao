import BlocosMarquee from '@/components/site/blocos-marquee';
import Reveal from '@/components/site/reveal';
import VideoPlayer from '@/components/site/video-player';
import type { HomeProjetosContent } from '@/types/home';

export default function HomeProjetos({ content }: { content: HomeProjetosContent }) {
    return (
        <section id="projetos" className="bg-white">
            <div className="mourao-container mourao-section grid grid-cols-1 items-center gap-10 lg:grid-cols-2">
                <Reveal>
                    <div>
                        <p className="mourao-eyebrow text-mourao-blue">{content.eyebrow}</p>
                        <h2 className="mt-3 mb-4 uppercase">{content.title}</h2>
                        {content.body.map((paragraph) => (
                            <p key={paragraph} className="mb-3 max-w-[520px]">
                                {paragraph}
                            </p>
                        ))}
                    </div>
                </Reveal>
                <Reveal from="right">
                    <VideoPlayer
                        src={content.videoSrc}
                        poster={content.videoPoster}
                        className="aspect-video w-full"
                    />
                </Reveal>
            </div>
            <BlocosMarquee />
        </section>
    );
}
