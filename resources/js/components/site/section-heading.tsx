import TextReveal from '@/components/site/text-reveal';
import { cn } from '@/lib/utils';

type SectionHeadingProps = {
    eyebrow?: string;
    title: string;
    description?: string;
    align?: 'left' | 'center';
    headingLevel?: 1 | 2;
    className?: string;
};

export default function SectionHeading({
    eyebrow,
    title,
    description,
    align = 'left',
    headingLevel = 2,
    className,
}: SectionHeadingProps) {
    const HeadingTag = headingLevel === 1 ? 'h1' : 'h2';
    const headingClassName =
        headingLevel === 1
            ? 'text-3xl font-semibold tracking-tight text-ibnes-navy md:text-4xl lg:text-5xl'
            : 'text-3xl font-semibold tracking-tight text-ibnes-navy md:text-4xl';

    return (
        <div
            className={cn(
                'max-w-2xl',
                align === 'center' && 'mx-auto text-center',
                className,
            )}
        >
            {eyebrow && (
                <TextReveal as="p" delay={0} className="mb-3 text-xs font-semibold uppercase tracking-[0.2em] text-ibnes-gold">
                    {eyebrow}
                </TextReveal>
            )}
            <TextReveal
                as={HeadingTag}
                delay={eyebrow ? 1 : 0}
                className={headingClassName}
            >
                {title}
            </TextReveal>
            {description && (
                <TextReveal
                    as="p"
                    delay={eyebrow ? 2 : 1}
                    className="mt-4 text-base leading-relaxed text-ibnes-muted md:text-lg"
                >
                    {description}
                </TextReveal>
            )}
        </div>
    );
}
