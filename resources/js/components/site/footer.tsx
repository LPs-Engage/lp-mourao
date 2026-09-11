import { usePage } from '@inertiajs/react';

export default function SiteFooter() {
    const { campaign } = usePage().props;
    const footer = campaign.footer;

    return (
        <footer className="bg-mourao-navy text-white">
            <div className="h-2.5 mourao-bg-orange" aria-hidden="true" />

            <div className="mourao-container grid grid-cols-1 gap-10 py-10 md:grid-cols-[1.2fr_1fr] md:gap-x-16 lg:grid-cols-[1.3fr_1fr_1fr] lg:gap-16 lg:py-16">
                <div>
                    <p className="text-lg font-extrabold tracking-[0.06em] uppercase md:text-[22px]">
                        {footer.name}
                    </p>
                    <p className="mt-1 text-xs font-extrabold tracking-[0.08em] uppercase text-mourao-orange md:text-sm">
                        {footer.role}
                    </p>
                    <img
                        src="/images/footer-1.png"
                        alt="55500"
                        className="mt-5 mb-4 block h-auto w-full max-w-[240px]"
                    />
                    <img
                        src="/images/footer-2.png"
                        alt="PSD"
                        className="block h-auto w-[90px]"
                    />
                </div>

                <nav className="flex flex-col items-start gap-3">
                    <p className="mb-1 text-xs font-extrabold tracking-[0.08em] uppercase md:text-sm">
                        Navegação
                    </p>
                    <a href="/#lucas" className="text-sm md:text-base">
                        Lucas
                    </a>
                    <a href="/#projetos" className="text-sm md:text-base">
                        Projetos
                    </a>
                    <a href="/#canal" className="text-sm md:text-base">
                        Canal da Baixada
                    </a>
                    <a href="/#onde" className="text-sm md:text-base">
                        Agenda
                    </a>
                    <a href="/#redes" className="text-sm md:text-base">
                        Redes
                    </a>
                    <a href="/#movimento" className="text-sm md:text-base">
                        Contato
                    </a>
                </nav>

                <div className="flex flex-col items-start gap-3">
                    <p className="mb-1 text-xs font-extrabold tracking-[0.08em] uppercase md:text-sm">
                        Participe
                    </p>
                    <a
                        href={campaign.whatsappGroupUrl}
                        className="inline-flex items-center justify-center rounded-full border border-white px-6 py-3 text-[11px] font-extrabold tracking-[0.06em] uppercase transition-colors hover:bg-white hover:text-mourao-navy md:text-[13px]"
                        rel="noopener noreferrer"
                    >
                        Falar no WhatsApp
                    </a>
                    <a
                        href="/#canal"
                        className="inline-flex items-center justify-center rounded-full border border-white px-6 py-3 text-[11px] font-extrabold tracking-[0.06em] uppercase transition-colors hover:bg-white hover:text-mourao-navy md:text-[13px]"
                    >
                        Canal da Baixada
                    </a>
                </div>
            </div>

            <div className="mx-auto flex w-full max-w-[1440px] flex-col gap-4 border-t border-white/16 px-4 py-5 pb-8 sm:px-8 md:px-8 lg:flex-row lg:items-center lg:justify-between">
                <p className="text-[10px] leading-relaxed opacity-80 md:text-xs lg:max-w-[80%]">
                    {footer.legal} —{' '}
                    <a href={footer.privacyHref} className="underline">
                        Política de Privacidade
                    </a>{' '}
                    —{' '}
                    <a href={footer.termsHref} className="underline">
                        Termos de Uso
                    </a>
                </p>
                <a
                    href="/#hero"
                    className="text-[11px] font-extrabold tracking-[0.06em] whitespace-nowrap uppercase md:text-xs"
                >
                    ↑ Voltar ao topo
                </a>
            </div>
        </footer>
    );
}
