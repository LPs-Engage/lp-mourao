type BlocosMarqueeProps = {
    alt?: string;
};

export default function BlocosMarquee({ alt = '' }: BlocosMarqueeProps) {
    return (
        <div className="w-full overflow-hidden leading-none">
            <div className="animate-mourao-marquee flex w-max motion-reduce:animate-none">
                <img
                    src="/images/blocos.png"
                    alt={alt}
                    className="h-[70px] w-auto shrink-0 md:h-[90px]"
                />
                <img
                    src="/images/blocos.png"
                    alt=""
                    aria-hidden="true"
                    className="h-[70px] w-auto shrink-0 md:h-[90px]"
                />
            </div>
        </div>
    );
}
