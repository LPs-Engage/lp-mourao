import { Form } from '@inertiajs/react';
import { useMemo } from 'react';
import { usePhoneMask } from '@/hooks/use-phone-mask';
import { readUtmFields } from '@/lib/utm';
import type { LandingPageConfig } from '@/types/landing-page';

type MinimalLeadTemplateProps = {
    landingPage: LandingPageConfig;
};

export default function MinimalLeadTemplate({ landingPage }: MinimalLeadTemplateProps) {
    const { value: phone, onChange: onPhoneChange } = usePhoneMask();
    const utm = useMemo(() => readUtmFields(), []);

    return (
        <section className="mourao-section bg-white">
            <div className="mourao-container max-w-3xl">
                <p className="mourao-eyebrow mb-3 text-mourao-orange">{landingPage.productName}</p>
                <h1 className="text-mourao-navy">{landingPage.heroTitle}</h1>
                <p className="mt-4 text-base leading-relaxed md:text-lg">{landingPage.heroSubtitle}</p>

                <div className="mt-10 space-y-6 rounded-2xl border border-mourao-gray bg-mourao-gray p-8">
                    {landingPage.sections.map((section, index) => (
                        <div key={`${index}-${section.title}`}>
                            <h2 className="text-sm font-semibold text-mourao-navy">{section.title}</h2>
                            <p className="mt-2 text-sm">{section.body}</p>
                        </div>
                    ))}

                    <Form
                        action={`/${landingPage.slug}/lead`}
                        method="post"
                        resetOnSuccess
                        className="flex flex-col gap-3 pt-4"
                    >
                        <input type="hidden" name="page_url" value={utm.page_url} />
                        <input
                            type="text"
                            name="website"
                            tabIndex={-1}
                            autoComplete="off"
                            className="hidden"
                            aria-hidden="true"
                        />
                        <label htmlFor="lp-name" className="text-xs font-extrabold uppercase">
                            Nome
                        </label>
                        <input
                            id="lp-name"
                            type="text"
                            name="name"
                            required
                            className="rounded-lg bg-mourao-navy px-4 py-3 text-white"
                        />
                        <label htmlFor="lp-phone" className="text-xs font-extrabold uppercase">
                            WhatsApp
                        </label>
                        <input
                            id="lp-phone"
                            type="tel"
                            name="phone"
                            required
                            value={phone}
                            onChange={onPhoneChange}
                            className="rounded-lg bg-mourao-navy px-4 py-3 text-white"
                        />
                        <label className="flex items-start gap-2 text-sm">
                            <input type="checkbox" name="lgpd" value="1" required className="mt-1 accent-mourao-orange" />
                            Concordo em receber comunicações do movimento.
                        </label>
                        <button type="submit" className="mourao-button max-w-none uppercase">
                            {landingPage.ctaLabel}
                        </button>
                    </Form>
                </div>
            </div>
        </section>
    );
}
