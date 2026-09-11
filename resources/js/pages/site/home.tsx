import SeoHead from '@/components/seo/seo-head';
import HomeAgenda from '@/components/site/home-agenda';
import HomeBaixada from '@/components/site/home-baixada';
import HomeCanal from '@/components/site/home-canal';
import HomeCapitulo from '@/components/site/home-capitulo';
import HomeFatos from '@/components/site/home-fatos';
import HomeHero from '@/components/site/home-hero';
import HomeLucas from '@/components/site/home-lucas';
import HomeMovimento from '@/components/site/home-movimento';
import HomeOnde from '@/components/site/home-onde';
import HomeProjetos from '@/components/site/home-projetos';
import HomeRedes from '@/components/site/home-redes';
import HomeTrabalho from '@/components/site/home-trabalho';
import type { HomeContent } from '@/types/home';
import type { PageSeo } from '@/types/seo';

type HomeProps = {
    seo: PageSeo;
    content: HomeContent;
};

export default function Home({ seo, content }: HomeProps) {
    return (
        <>
            <SeoHead seo={seo} />
            <HomeHero content={content.hero} />
            <HomeLucas content={content.lucas} />
            <HomeTrabalho content={content.trabalho} />
            <HomeProjetos content={content.projetos} />
            <HomeAgenda content={content.agenda} />
            <HomeOnde content={content.onde} />
            <HomeFatos content={content.fatos} />
            <HomeBaixada content={content.baixada} />
            <HomeCapitulo content={content.capitulo} />
            <HomeCanal content={content.canal} />
            <HomeRedes content={content.redes} />
            <HomeMovimento content={content.movimento} />
        </>
    );
}
