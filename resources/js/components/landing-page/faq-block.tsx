import FaqSection from '@/components/site/faq-section';
import type { FaqBlock } from '@/types/faq';

type LandingPageFaqBlockProps = {
    faq: FaqBlock | null;
    className?: string;
};

export default function LandingPageFaqBlock({ faq, className }: LandingPageFaqBlockProps) {
    if (!faq) {
        return null;
    }

    return <FaqSection faq={faq} className={className} />;
}
