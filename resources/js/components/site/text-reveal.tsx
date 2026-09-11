import { usePageMotion } from '@/components/site/page-motion';
import { cn } from '@/lib/utils';

type TextRevealProps = {
    children: React.ReactNode;
    className?: string;
    as?: 'p' | 'h1' | 'h2' | 'h3' | 'div' | 'span';
    delay?: number;
};

export default function TextReveal({
    children,
    className,
    as: Component = 'div',
    delay = 0,
}: TextRevealProps) {
    const { phase } = usePageMotion();

    return (
        <Component
            style={{ transitionDelay: `${delay * 80}ms` }}
            className={cn(
                'transition-all duration-700 ease-out motion-reduce:transition-none motion-reduce:translate-x-0 motion-reduce:opacity-100',
                phase === 'visible' && 'translate-x-0 opacity-100',
                phase === 'enter-from-left' && '-translate-x-8 opacity-0',
                phase === 'exit-to-right' && 'translate-x-8 opacity-0',
                className,
            )}
        >
            {children}
        </Component>
    );
}
