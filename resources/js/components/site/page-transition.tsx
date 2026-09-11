import { PageMotionProvider } from '@/components/site/page-motion';

type PageTransitionProps = {
    children: React.ReactNode;
    className?: string;
};

export default function PageTransition({ children, className }: PageTransitionProps) {
    return (
        <PageMotionProvider>
            <div className={className}>{children}</div>
        </PageMotionProvider>
    );
}
