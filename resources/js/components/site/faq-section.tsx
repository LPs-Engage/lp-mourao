import FaqAccordion from '@/components/site/faq-accordion';
import SectionHeading from '@/components/site/section-heading';
import { cn } from '@/lib/utils';
import type { FaqBlock } from '@/types/faq';

type FaqSectionProps = {
    faq: FaqBlock;
    className?: string;
};

export default function FaqSection({ faq, className }: FaqSectionProps) {
    if (faq.items.length === 0) {
        return null;
    }

    return (
        <section className={cn('ibnes-section', className)}>
            <div className="ibnes-container max-w-3xl">
                <SectionHeading
                    eyebrow={faq.eyebrow || undefined}
                    title={faq.title}
                    description={faq.description || undefined}
                    align="center"
                    className="mb-10"
                />
                <FaqAccordion items={faq.items} />
            </div>
        </section>
    );
}
