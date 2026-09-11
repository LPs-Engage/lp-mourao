import { useInView } from '@/hooks/use-in-view';
import { cn } from '@/lib/utils';

type RevealProps = {
    children: React.ReactNode;
    className?: string;
    from?: 'up' | 'left' | 'right';
};

export default function Reveal({
    children,
    className,
    from = 'up',
}: RevealProps) {
    const { ref, isVisible } = useInView<HTMLDivElement>();

    return (
        <div
            ref={ref}
            className={cn(
                'transition-all duration-700 ease-out motion-reduce:translate-x-0 motion-reduce:translate-y-0 motion-reduce:opacity-100',
                isVisible
                    ? 'translate-x-0 translate-y-0 opacity-100'
                    : from === 'left'
                      ? '-translate-x-8 opacity-0'
                      : from === 'right'
                        ? 'translate-x-8 opacity-0'
                        : 'translate-y-6 opacity-0',
                className,
            )}
        >
            {children}
        </div>
    );
}
