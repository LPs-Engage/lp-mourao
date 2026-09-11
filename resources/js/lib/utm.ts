export type UtmFields = {
    page_url: string;
    utm_source: string;
    utm_medium: string;
    utm_campaign: string;
    utm_term: string;
    utm_content: string;
};

export function readUtmFields(): UtmFields {
    if (typeof window === 'undefined') {
        return {
            page_url: '',
            utm_source: '',
            utm_medium: '',
            utm_campaign: '',
            utm_term: '',
            utm_content: '',
        };
    }

    const params = new URLSearchParams(window.location.search);

    return {
        page_url: window.location.href,
        utm_source: params.get('utm_source') ?? '',
        utm_medium: params.get('utm_medium') ?? '',
        utm_campaign: params.get('utm_campaign') ?? '',
        utm_term: params.get('utm_term') ?? '',
        utm_content: params.get('utm_content') ?? '',
    };
}
