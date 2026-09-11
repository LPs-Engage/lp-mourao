import { usePage } from '@inertiajs/react';
import SeoHead from '@/components/seo/seo-head';
import type { PageSeo } from '@/types/seo';

type ThankYouProps = {
    seo: PageSeo;
    whatsappGroupUrl: string;
};

export default function ThankYou({ seo, whatsappGroupUrl }: ThankYouProps) {
    const { campaign } = usePage().props;
    const href = whatsappGroupUrl || campaign.whatsappGroupUrl;

    return (
        <>
            <SeoHead seo={seo} />
            <section className="mourao-bg-blue text-white">
                <div className="mourao-container mourao-section mx-auto max-w-2xl text-center">
                    <p className="mourao-eyebrow text-mourao-orange">Movimento</p>
                    <h1 className="mt-4 uppercase text-white">Cadastro realizado.</h1>
                    <p className="mt-6 text-[clamp(16px,2vw,20px)]">
                        Quer acompanhar ainda mais de perto?
                    </p>
                    <a
                        href={href}
                        rel="noopener noreferrer"
                        className="mt-8 inline-flex items-center justify-center rounded-lg bg-white px-7 py-4 text-sm font-extrabold tracking-[0.04em] uppercase text-mourao-blue"
                    >
                        Entrar no WhatsApp
                    </a>
                </div>
            </section>
        </>
    );
}
