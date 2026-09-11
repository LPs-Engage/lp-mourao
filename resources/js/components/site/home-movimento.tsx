import { Form, usePage } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';
import BlocosMarquee from '@/components/site/blocos-marquee';
import Reveal from '@/components/site/reveal';
import { usePhoneMask } from '@/hooks/use-phone-mask';
import { readUtmFields } from '@/lib/utm';
import type { HomeMovimentoContent } from '@/types/home';

export default function HomeMovimento({ content }: { content: HomeMovimentoContent }) {
    const { value: phone, onChange: onPhoneChange } = usePhoneMask();
    const utm = useMemo(() => readUtmFields(), []);
    const { errors, flash, campaign } = usePage().props as {
        errors: Record<string, string>;
        flash?: { success?: string | null };
        campaign: { whatsappGroupUrl: string };
    };
    const [submitted, setSubmitted] = useState(Boolean(flash?.success));
    const whatsappHref = campaign.whatsappGroupUrl;

    useEffect(() => {
        if (flash?.success) {
            setSubmitted(true);
        }
    }, [flash?.success]);

    useEffect(() => {
        if (!submitted) {
            return;
        }

        document.getElementById('movimento')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, [submitted]);

    return (
        <section id="movimento" className="mourao-bg-blue text-white">
            <div className="mourao-container mourao-section grid grid-cols-1 items-center gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">
                <Reveal>
                    <div>
                        <p className="mourao-eyebrow mb-3 text-mourao-orange">{content.eyebrow}</p>
                        <h2 className="mb-4 max-w-[560px] uppercase text-white">{content.title}</h2>
                        <p className="max-w-[460px] text-[clamp(14px,2vw,18px)] leading-snug">
                            {content.body}
                        </p>
                        <div className="relative mt-8 min-h-[320px] w-full max-w-[520px] md:min-h-[380px] lg:min-h-[420px] lg:max-w-[580px]">
                            <img
                                src="/images/form-img-1.png"
                                alt="Lucas Mourão em campanha, acenando de um carro e cumprimentando apoiadores."
                                className="absolute top-5 left-0 z-1 w-[64%] drop-shadow-[0_10px_20px_rgba(11,17,87,0.28)]"
                            />
                            <img
                                src="/images/form-img-2.png"
                                alt="Moradores da Baixada acompanhando a campanha."
                                className="absolute top-0 right-0 z-2 w-[64%] drop-shadow-[0_10px_20px_rgba(11,17,87,0.28)]"
                            />
                            <img
                                src="/images/form-icon-2.png"
                                alt=""
                                aria-hidden="true"
                                className="pointer-events-none absolute bottom-0 left-[6%] z-3 w-[clamp(110px,16vw,170px)] drop-shadow-[0_8px_16px_rgba(11,17,87,0.25)]"
                            />
                            <img
                                src="/images/form-icon-1.png"
                                alt=""
                                aria-hidden="true"
                                className="pointer-events-none absolute top-[36%] right-[8%] z-3 w-[clamp(110px,16vw,170px)] drop-shadow-[0_8px_16px_rgba(11,17,87,0.25)]"
                            />
                        </div>
                    </div>
                </Reveal>

                <Reveal from="right">
                    {submitted ? (
                        <div className="flex flex-col justify-center" aria-live="polite">
                            <h2 className="max-w-[12ch] uppercase leading-[1.05] text-white">
                                Cadastro realizado.
                            </h2>
                            <p className="mt-5 max-w-[28ch] text-[clamp(16px,2vw,20px)] leading-snug">
                                Quer acompanhar ainda mais de perto?
                            </p>
                            <a
                                href={whatsappHref}
                                rel="noopener noreferrer"
                                className="mt-8 inline-flex w-fit items-center justify-center rounded-lg bg-white px-7 py-4 text-sm font-extrabold tracking-[0.04em] uppercase text-mourao-blue transition hover:bg-white/90"
                            >
                                Entrar no WhatsApp
                            </a>
                        </div>
                    ) : (
                        <Form
                            action="/cadastro"
                            method="post"
                            resetOnSuccess
                            options={{ preserveScroll: true }}
                            onSuccess={() => setSubmitted(true)}
                            className="flex flex-col gap-2.5 rounded-2xl bg-white p-6 text-mourao-navy lg:p-8"
                        >
                            <input type="hidden" name="page_url" value={utm.page_url} />
                            <input type="hidden" name="utm_source" value={utm.utm_source} />
                            <input type="hidden" name="utm_medium" value={utm.utm_medium} />
                            <input type="hidden" name="utm_campaign" value={utm.utm_campaign} />
                            <input type="hidden" name="utm_term" value={utm.utm_term} />
                            <input type="hidden" name="utm_content" value={utm.utm_content} />
                            <input
                                type="text"
                                name="website"
                                tabIndex={-1}
                                autoComplete="off"
                                className="hidden"
                                aria-hidden="true"
                            />

                            <label htmlFor="nome" className="text-[clamp(10px,1.6vw,12px)] font-extrabold tracking-[0.06em] uppercase">
                                Nome
                            </label>
                            <input
                                id="nome"
                                type="text"
                                name="name"
                                required
                                placeholder="Seu nome"
                                className="mb-2 w-full rounded-lg border-0 bg-mourao-navy px-4 py-3.5 text-[clamp(14px,2vw,16px)] text-white placeholder:text-white/55"
                            />
                            {errors.name ? <p className="text-sm text-red-600">{errors.name}</p> : null}

                            <label htmlFor="telefone" className="text-[clamp(10px,1.6vw,12px)] font-extrabold tracking-[0.06em] uppercase">
                                WhatsApp
                            </label>
                            <input
                                id="telefone"
                                type="tel"
                                name="phone"
                                required
                                value={phone}
                                onChange={onPhoneChange}
                                placeholder="(00) 00000-0000"
                                className="mb-2 w-full rounded-lg border-0 bg-mourao-navy px-4 py-3.5 text-[clamp(14px,2vw,16px)] text-white placeholder:text-white/55"
                            />
                            {errors.phone ? <p className="text-sm text-red-600">{errors.phone}</p> : null}

                            <label htmlFor="cidade" className="text-[clamp(10px,1.6vw,12px)] font-extrabold tracking-[0.06em] uppercase">
                                Cidade
                            </label>
                            <input
                                id="cidade"
                                type="text"
                                name="city"
                                required
                                placeholder="Sua cidade"
                                className="mb-2 w-full rounded-lg border-0 bg-mourao-navy px-4 py-3.5 text-[clamp(14px,2vw,16px)] text-white placeholder:text-white/55"
                            />
                            {errors.city ? <p className="text-sm text-red-600">{errors.city}</p> : null}

                            <label htmlFor="bairro" className="text-[clamp(10px,1.6vw,12px)] font-extrabold tracking-[0.06em] uppercase">
                                Bairro
                            </label>
                            <input
                                id="bairro"
                                type="text"
                                name="neighborhood"
                                required
                                placeholder="Seu bairro"
                                className="mb-2 w-full rounded-lg border-0 bg-mourao-navy px-4 py-3.5 text-[clamp(14px,2vw,16px)] text-white placeholder:text-white/55"
                            />
                            {errors.neighborhood ? (
                                <p className="text-sm text-red-600">{errors.neighborhood}</p>
                            ) : null}

                            <label className="mt-2 mb-3 flex items-start gap-2.5 text-[clamp(11px,1.6vw,13px)] font-normal tracking-normal text-[#6B6B6B] normal-case">
                                <input
                                    type="checkbox"
                                    name="lgpd"
                                    value="1"
                                    required
                                    className="mt-0.5 size-[18px] shrink-0 accent-mourao-orange"
                                />
                                Concordo em receber comunicações sobre agenda, projetos e ações do movimento.
                            </label>
                            {errors.lgpd ? <p className="text-sm text-red-600">{errors.lgpd}</p> : null}
                            {errors.form ? <p className="text-sm text-red-600">{errors.form}</p> : null}

                            <button type="submit" className="mourao-button mt-1 max-w-none uppercase">
                                Quero fazer parte
                            </button>
                        </Form>
                    )}
                </Reveal>
            </div>
            <BlocosMarquee />
        </section>
    );
}
