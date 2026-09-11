import { useState } from 'react';

const NAV_LINKS = [
    { href: '/#lucas', label: 'Lucas' },
    { href: '/#trabalho', label: 'Trabalho' },
    { href: '/#projetos', label: 'Projetos' },
    { href: '/#baixada', label: 'Baixada' },
    { href: '/#onde', label: 'Agenda' },
];

export default function SiteHeader() {
    const [open, setOpen] = useState(false);

    return (
        <header className="relative z-20 bg-mourao-navy text-white">
            <div
                className={`mourao-container flex min-h-16 items-center justify-between gap-4 py-3 md:min-h-[72px] md:py-4 ${open ? 'flex-wrap' : ''}`}
            >
                <a
                    href="/#hero"
                    className="shrink-0 text-sm font-extrabold tracking-[0.08em] whitespace-nowrap uppercase md:text-lg"
                >
                    Lucas Mourão
                </a>

                <nav
                    id="site-nav"
                    className={`${open ? 'order-4 flex w-full flex-col items-start gap-4 py-2.5 pb-4' : 'hidden'} items-center gap-[30px] lg:flex`}
                >
                    {NAV_LINKS.map((link) => (
                        <a
                            key={link.href}
                            href={link.href}
                            className="text-xs font-medium whitespace-nowrap hover:text-mourao-yellow md:text-base"
                            onClick={() => setOpen(false)}
                        >
                            {link.label}
                        </a>
                    ))}
                </nav>

                <div className="ml-auto flex shrink-0 items-center gap-4">
                    <a
                        href="/#canal"
                        className="hidden text-[10px] font-bold tracking-[0.06em] uppercase hover:text-mourao-yellow lg:inline md:text-sm"
                    >
                        Canal da Baixada
                    </a>
                    <a
                        href="/#movimento"
                        className="rounded-lg border border-white bg-white px-5 py-2.5 text-xs font-extrabold tracking-[0.04em] whitespace-nowrap uppercase text-mourao-navy transition-colors hover:bg-transparent hover:text-white md:text-sm"
                    >
                        Faça parte
                    </a>
                </div>

                <button
                    type="button"
                    className="flex h-10 w-10 shrink-0 flex-col justify-center gap-1.5 p-2 lg:hidden"
                    aria-label={open ? 'Fechar menu' : 'Abrir menu'}
                    aria-expanded={open}
                    aria-controls="site-nav"
                    onClick={() => setOpen((value) => !value)}
                >
                    <span
                        className={`block h-0.5 w-full rounded-sm bg-white transition-transform ${open ? 'translate-y-2 rotate-45' : ''}`}
                    />
                    <span
                        className={`block h-0.5 w-full rounded-sm bg-white transition-opacity ${open ? 'opacity-0' : ''}`}
                    />
                    <span
                        className={`block h-0.5 w-full rounded-sm bg-white transition-transform ${open ? '-translate-y-2 -rotate-45' : ''}`}
                    />
                </button>
            </div>
        </header>
    );
}
