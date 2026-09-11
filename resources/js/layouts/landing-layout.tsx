import PageTransition from '@/components/site/page-transition';

export default function LandingLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    return (
        <div className="min-h-screen bg-white text-mourao-navy">
            <main>
                <PageTransition>{children}</PageTransition>
            </main>
        </div>
    );
}
