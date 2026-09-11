import SeoHead from '@/components/seo/seo-head';
import type { PageSeo } from '@/types/seo';

type LandingPageNotFoundProps = {
    seo: PageSeo;
};

export default function LandingPageNotFound({ seo }: LandingPageNotFoundProps) {
    return (
        <>
            <SeoHead seo={seo} />

            <section className="mourao-section bg-white">
                <div className="mourao-container max-w-2xl text-center">
                    <p className="mourao-eyebrow mb-3 text-mourao-orange">55500</p>
                    <h1 className="text-mourao-navy">Página não encontrada</h1>
                    <p className="mt-6 text-base leading-relaxed md:text-lg">
                        O link que você acessou não existe ou não está mais disponível.
                    </p>
                    <div className="mt-10">
                        <a href="/" className="mourao-button max-w-xs uppercase">
                            Voltar ao site
                        </a>
                    </div>
                </div>
            </section>
        </>
    );
}
