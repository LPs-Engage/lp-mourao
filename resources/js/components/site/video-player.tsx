import { useRef, useState } from 'react';

type VideoPlayerProps = {
    src?: string | null;
    poster?: string | null;
    className?: string;
};

export default function VideoPlayer({ src, poster, className }: VideoPlayerProps) {
    const videoRef = useRef<HTMLVideoElement | null>(null);
    const [playing, setPlaying] = useState(false);

    const play = () => {
        const video = videoRef.current;

        if (!video || !src) {
            return;
        }

        void video.play();
        setPlaying(true);
    };

    return (
        <figure className={`relative overflow-hidden rounded-2xl bg-mourao-navy/30 ${className ?? ''}`}>
            <video
                ref={videoRef}
                playsInline
                preload="metadata"
                src={src ?? undefined}
                poster={poster ?? undefined}
                className="block h-full w-full object-cover"
                onPause={() => setPlaying(false)}
                onPlay={() => setPlaying(true)}
            />
            <button
                type="button"
                aria-label="Reproduzir vídeo"
                onClick={play}
                className={`absolute top-1/2 left-1/2 size-[60px] -translate-x-1/2 -translate-y-1/2 rounded-full border-0 bg-mourao-navy transition hover:scale-105 lg:size-[70px] ${playing ? 'pointer-events-none opacity-0' : ''}`}
            >
                <span className="mx-auto ml-1.5 block h-0 w-0 border-y-[10px] border-l-4 border-y-transparent border-l-[16px] border-l-white" />
            </button>
        </figure>
    );
}
