import type { Auth } from '@/types/auth';
import type { SeoDefaults } from '@/types/seo';
import type { CampaignShell } from '@/types/home';
import type { TrackingSharedProps } from '@/types/tracking';

declare module 'react' {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    interface InputHTMLAttributes<T> {
        passwordrules?: string;
    }
}

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            auth: Auth;
            sidebarOpen: boolean;
            tracking: TrackingSharedProps;
            seoDefaults: SeoDefaults;
            campaign: CampaignShell;
            [key: string]: unknown;
        };
    }
}
