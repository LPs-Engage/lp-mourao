export type ImageAsset = {
    src: string;
    alt: string;
};

export type CampaignFooter = {
    name: string;
    role: string;
    legal: string;
    privacyHref: string;
    termsHref: string;
};

export type CampaignShell = {
    whatsappGroupUrl: string;
    footer: CampaignFooter;
};

export type HomeHeroContent = {
    title: string;
    image: string;
    imageMobile: string;
    imageAlt: string;
    marqueeAlt: string;
};

export type HomeLucasContent = {
    eyebrow: string;
    title: string;
    titleAccent: string;
    items: Array<{
        icon: string;
        iconAlt: string;
        title: string;
        body: string;
    }>;
};

export type HomeTrabalhoContent = {
    eyebrow: string;
    title: string;
    items: Array<{
        title: string;
        body: string;
        images: ImageAsset[];
    }>;
};

export type HomeProjetosContent = {
    eyebrow: string;
    title: string;
    body: string[];
    videoSrc: string | null;
    videoPoster: string | null;
};

export type HomeAgendaContent = {
    eyebrow: string;
    title: string;
    body: string;
    items: Array<{
        dia: number;
        tema: string;
        title: string;
        desc: string;
        href: string;
    }>;
};

export type HomeOndeContent = {
    eyebrow: string;
    title: string;
    titleAccent: string;
    image: string;
    ctaLabel: string;
    ctaHref: string;
    items: Array<{
        data: string;
        local: string;
        title: string;
        hora: string;
        href: string;
    }>;
};

export type HomeFatosContent = {
    eyebrow: string;
    title: string;
    titleAccent: string;
    body: string;
    items: Array<{
        icon: string;
        title: string;
        desc: string;
        cta: string;
        href: string;
    }>;
};

export type HomeBaixadaContent = {
    eyebrow: string;
    title: string;
    titleAccent: string;
    body: string;
    gallery: ImageAsset[];
};

export type HomeCapituloItem = {
    icon: string;
    title: string;
    desafio: string;
    proposta: string;
    estado: string;
    copy: {
        desafio: string;
        proposta: string;
        estado: string;
        body: string[];
    };
};

export type HomeCapituloContent = {
    eyebrow: string;
    title: string;
    items: HomeCapituloItem[];
};

export type HomeCanalContent = {
    eyebrow: string;
    title: string;
    body: string[];
    highlight: string;
    primaryCtaLabel: string;
    primaryCtaHref: string;
    secondaryCtaLabel: string;
    secondaryCtaHref: string;
    caption: string;
    image: string;
    imageAlt: string;
};

export type HomeRedesContent = {
    eyebrow: string;
    title: string;
    body: string;
    links: Array<{ label: string; href: string }>;
    videos: Array<{ src: string | null; poster: string | null }>;
};

export type HomeMovimentoContent = {
    eyebrow: string;
    title: string;
    body: string;
};

export type HomeContent = {
    whatsappGroupUrl: string;
    hero: HomeHeroContent;
    lucas: HomeLucasContent;
    trabalho: HomeTrabalhoContent;
    projetos: HomeProjetosContent;
    agenda: HomeAgendaContent;
    onde: HomeOndeContent;
    fatos: HomeFatosContent;
    baixada: HomeBaixadaContent;
    capitulo: HomeCapituloContent;
    canal: HomeCanalContent;
    redes: HomeRedesContent;
    movimento: HomeMovimentoContent;
    footer: CampaignFooter;
};
