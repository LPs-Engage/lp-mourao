import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useCallback, useEffect, useRef, useState } from 'react';
import { cn } from '@/lib/utils';

export type SlideCarouselItem = {
    image?: string | null;
    imageAlt?: string;
    eyebrow?: string;
    title?: string;
    body?: string;
};

type SlideCarouselProps = {
    slides: SlideCarouselItem[];
    className?: string;
    autoPlayMs?: number;
    variant?: 'hero' | 'embedded';
};

const SWIPE_THRESHOLD_PX = 48;
const DEFAULT_AUTO_PLAY_MS = 6000;

function hasText(slide: SlideCarouselItem): boolean {
    return Boolean(slide.title?.trim() || slide.body?.trim() || slide.eyebrow?.trim());
}

function hasImage(slide: SlideCarouselItem): boolean {
    return Boolean(slide.image?.trim());
}

function SlideContent({
    slide,
    variant,
}: {
    slide: SlideCarouselItem;
    variant: 'hero' | 'embedded';
}) {
    const withImage = hasImage(slide);
    const withText = hasText(slide);
    const heightClass =
        variant === 'hero'
            ? 'min-h-[min(70vh,42rem)]'
            : 'min-h-[16rem] sm:min-h-[20rem] md:min-h-[24rem]';

    if (withImage && withText) {
        return (
            <div className={cn('grid w-full grid-cols-1 md:grid-cols-2', heightClass)}>
                <div className="relative min-h-48 md:min-h-full">
                    <img
                        src={slide.image!}
                        alt={slide.imageAlt?.trim() || slide.title?.trim() || 'Slide'}
                        className="absolute inset-0 size-full object-cover"
                        loading="lazy"
                    />
                </div>
                <div className="flex flex-col justify-center bg-white px-6 py-8 sm:px-10 md:px-12">
                    {slide.eyebrow?.trim() && (
                        <p className="mb-3 text-xs font-semibold uppercase tracking-[0.2em] text-ibnes-gold">
                            {slide.eyebrow}
                        </p>
                    )}
                    {slide.title?.trim() && (
                        <h2
                            className={cn(
                                'font-semibold leading-tight text-ibnes-navy',
                                variant === 'hero'
                                    ? 'text-2xl sm:text-3xl lg:text-4xl'
                                    : 'text-xl sm:text-2xl',
                            )}
                        >
                            {slide.title}
                        </h2>
                    )}
                    {slide.body?.trim() && (
                        <p className="mt-4 text-sm leading-relaxed text-ibnes-muted md:text-base">
                            {slide.body}
                        </p>
                    )}
                </div>
            </div>
        );
    }

    if (withImage) {
        return (
            <div className={cn('relative w-full', heightClass)}>
                <img
                    src={slide.image!}
                    alt={slide.imageAlt?.trim() || 'Slide'}
                    className="absolute inset-0 size-full object-cover"
                    loading="lazy"
                />
            </div>
        );
    }

    return (
        <div
            className={cn(
                'flex w-full flex-col justify-center bg-linear-to-br from-ibnes-navy via-ibnes-navy-light to-ibnes-gold/20 px-6 py-10 text-white sm:px-12',
                heightClass,
            )}
        >
            {slide.eyebrow?.trim() && (
                <p className="mb-3 text-xs font-semibold uppercase tracking-[0.25em] text-ibnes-gold">
                    {slide.eyebrow}
                </p>
            )}
            {slide.title?.trim() && (
                <h2
                    className={cn(
                        'max-w-3xl font-semibold leading-tight',
                        variant === 'hero' ? 'text-3xl sm:text-4xl lg:text-5xl' : 'text-2xl sm:text-3xl',
                    )}
                >
                    {slide.title}
                </h2>
            )}
            {slide.body?.trim() && (
                <p className="mt-4 max-w-2xl text-sm leading-relaxed text-white/80 md:text-base">
                    {slide.body}
                </p>
            )}
        </div>
    );
}

function usePrefersReducedMotion(): boolean {
    const [prefersReducedMotion, setPrefersReducedMotion] = useState(false);

    useEffect(() => {
        const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
        const update = () => setPrefersReducedMotion(mediaQuery.matches);

        update();
        mediaQuery.addEventListener('change', update);

        return () => mediaQuery.removeEventListener('change', update);
    }, []);

    return prefersReducedMotion;
}

export default function SlideCarousel({
    slides,
    className,
    autoPlayMs = DEFAULT_AUTO_PLAY_MS,
    variant = 'hero',
}: SlideCarouselProps) {
    const [activeIndex, setActiveIndex] = useState(0);
    const [isPaused, setIsPaused] = useState(false);
    const touchStartX = useRef<number | null>(null);
    const prefersReducedMotion = usePrefersReducedMotion();

    const total = slides.length;
    const canNavigate = total > 1;
    const shouldAutoPlay = canNavigate && autoPlayMs > 0 && !prefersReducedMotion && !isPaused;

    const goTo = useCallback(
        (index: number) => {
            if (!canNavigate) {
                return;
            }

            const normalized = (index + total) % total;
            setActiveIndex(normalized);
        },
        [canNavigate, total],
    );

    const goNext = useCallback(() => goTo(activeIndex + 1), [activeIndex, goTo]);
    const goPrev = useCallback(() => goTo(activeIndex - 1), [activeIndex, goTo]);

    useEffect(() => {
        if (!shouldAutoPlay) {
            return;
        }

        const timer = window.setInterval(goNext, autoPlayMs);

        return () => window.clearInterval(timer);
    }, [autoPlayMs, goNext, shouldAutoPlay]);

    useEffect(() => {
        if (!shouldAutoPlay) {
            return;
        }

        const handleVisibilityChange = () => {
            setIsPaused(document.hidden);
        };

        document.addEventListener('visibilitychange', handleVisibilityChange);

        return () => document.removeEventListener('visibilitychange', handleVisibilityChange);
    }, [shouldAutoPlay]);

    const handleTouchStart = (event: React.TouchEvent<HTMLDivElement>) => {
        touchStartX.current = event.changedTouches[0]?.clientX ?? null;
    };

    const handleTouchEnd = (event: React.TouchEvent<HTMLDivElement>) => {
        if (touchStartX.current === null) {
            return;
        }

        const endX = event.changedTouches[0]?.clientX ?? touchStartX.current;
        const delta = endX - touchStartX.current;
        touchStartX.current = null;

        if (Math.abs(delta) < SWIPE_THRESHOLD_PX) {
            return;
        }

        if (delta < 0) {
            goNext();
        } else {
            goPrev();
        }
    };

    if (total === 0) {
        return null;
    }

    return (
        <div
            className={cn('relative overflow-hidden rounded-2xl border border-ibnes-border bg-white shadow-sm', className)}
            role="region"
            aria-roledescription="carousel"
            aria-label="Apresentação de slides"
            onMouseEnter={() => setIsPaused(true)}
            onMouseLeave={() => setIsPaused(false)}
            onFocusCapture={() => setIsPaused(true)}
            onBlurCapture={(event) => {
                if (!event.currentTarget.contains(event.relatedTarget)) {
                    setIsPaused(false);
                }
            }}
            onTouchStart={canNavigate ? handleTouchStart : undefined}
            onTouchEnd={canNavigate ? handleTouchEnd : undefined}
        >
            <div className="overflow-hidden">
                <div
                    className="flex motion-reduce:transition-none transition-transform duration-500 ease-out"
                    style={{ transform: `translateX(-${activeIndex * 100}%)` }}
                >
                    {slides.map((slide, index) => (
                        <div
                            key={`${index}-${slide.title ?? slide.image ?? 'slide'}`}
                            className="w-full shrink-0"
                            role="group"
                            aria-roledescription="slide"
                            aria-label={`Slide ${index + 1} de ${total}`}
                            aria-hidden={index !== activeIndex}
                        >
                            <SlideContent slide={slide} variant={variant} />
                        </div>
                    ))}
                </div>
            </div>

            {canNavigate && (
                <>
                    <button
                        type="button"
                        onClick={goPrev}
                        className="absolute left-3 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/90 p-2 text-ibnes-navy shadow-md backdrop-blur-sm transition-colors hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ibnes-gold sm:left-4"
                        aria-label="Slide anterior"
                    >
                        <ChevronLeft className="size-5" aria-hidden="true" />
                    </button>
                    <button
                        type="button"
                        onClick={goNext}
                        className="absolute right-3 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/90 p-2 text-ibnes-navy shadow-md backdrop-blur-sm transition-colors hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ibnes-gold sm:right-4"
                        aria-label="Próximo slide"
                    >
                        <ChevronRight className="size-5" aria-hidden="true" />
                    </button>

                    <div className="absolute bottom-4 left-1/2 z-10 flex -translate-x-1/2 gap-2">
                        {slides.map((slide, index) => (
                            <button
                                key={`dot-${index}-${slide.title ?? slide.image ?? 'slide'}`}
                                type="button"
                                onClick={() => goTo(index)}
                                className={cn(
                                    'size-2.5 rounded-full transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ibnes-gold',
                                    index === activeIndex
                                        ? 'bg-ibnes-gold'
                                        : 'bg-white/70 hover:bg-white',
                                )}
                                aria-label={`Ir para o slide ${index + 1}`}
                                aria-current={index === activeIndex}
                            />
                        ))}
                    </div>
                </>
            )}
        </div>
    );
}
