import SiteFooter from '@/components/site/footer';
import SiteHeader from '@/components/site/header';
import PageTransition from '@/components/site/page-transition';

export default function SiteLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    return (
        <div className="flex min-h-screen flex-col bg-white font-sans text-mourao-navy">
            <SiteHeader />
            <main className="flex-1">
                <PageTransition>{children}</PageTransition>
            </main>
            <SiteFooter />
        </div>
    );
}
