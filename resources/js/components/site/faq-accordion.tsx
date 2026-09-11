import { Search } from 'lucide-react';
import { useState } from 'react';
import { cn } from '@/lib/utils';
import type { FaqItem } from '@/types/faq';

type FaqAccordionProps = {
    items: FaqItem[];
    className?: string;
};

export default function FaqAccordion({ items, className }: FaqAccordionProps) {
    const [openIndex, setOpenIndex] = useState<number | null>(null);

    if (items.length === 0) {
        return null;
    }

    return (
        <div className={cn('space-y-3', className)}>
            {items.map((item, index) => {
                const isOpen = openIndex === index;
                const panelId = `faq-panel-${index}`;
                const triggerId = `faq-trigger-${index}`;

                return (
                    <article
                        key={`${index}-${item.question}`}
                        className="overflow-hidden rounded-2xl border border-ibnes-border bg-white shadow-sm"
                    >
                        <button
                            type="button"
                            id={triggerId}
                            aria-expanded={isOpen}
                            aria-controls={panelId}
                            onClick={() => setOpenIndex(isOpen ? null : index)}
                            className="flex w-full cursor-pointer items-center gap-4 px-5 py-4 text-left transition-colors hover:bg-ibnes-surface/60 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ibnes-gold focus-visible:ring-offset-2 sm:px-6 sm:py-5"
                        >
                            <span className="flex-1 text-base font-semibold text-ibnes-navy sm:text-lg">
                                {item.question}
                            </span>
                            <span
                                className={cn(
                                    'flex size-9 shrink-0 items-center justify-center rounded-full bg-ibnes-surface text-ibnes-navy transition-all duration-350 ease-in-out motion-reduce:transition-none',
                                    isOpen && 'rotate-90 bg-ibnes-gold/20',
                                )}
                                aria-hidden="true"
                            >
                                <Search className="size-4" />
                            </span>
                        </button>

                        <div
                            id={panelId}
                            role="region"
                            aria-labelledby={triggerId}
                            aria-hidden={!isOpen}
                            className={cn(
                                'grid motion-reduce:transition-none transition-[grid-template-rows] duration-350 ease-in-out',
                                isOpen ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]',
                            )}
                        >
                            <div className="min-h-0 overflow-hidden">
                                <div
                                    className={cn(
                                        'border-t border-ibnes-border/70 px-5 pb-5 pt-3 motion-reduce:transition-none transition-[opacity,transform] duration-350 ease-in-out sm:px-6 sm:pb-6',
                                        isOpen ? 'translate-y-0 opacity-100' : '-translate-y-1 opacity-0',
                                    )}
                                >
                                    <p className="text-sm leading-relaxed text-ibnes-muted sm:text-base">
                                        {item.answer}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>
                );
            })}
        </div>
    );
}
