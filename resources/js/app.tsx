import { createInertiaApp } from '@inertiajs/react';
import GoogleTagManager from '@/components/tracking/google-tag-manager';
import Hotjar from '@/components/tracking/hotjar';
import MetaPixel from '@/components/tracking/meta-pixel';
import { Toaster } from '@/components/ui/sonner';
import { TooltipProvider } from '@/components/ui/tooltip';
import { initializeTheme } from '@/hooks/use-appearance';
import { resolvePageLayout } from '@/layout-resolver';
import type { TrackingSharedProps } from '@/types/tracking';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => title || appName,
    layout: resolvePageLayout,
    strictMode: true,
    withApp(app, { page }) {
        const tracking = page.props.tracking as TrackingSharedProps | undefined;

        return (
            <TooltipProvider delayDuration={0}>
                <GoogleTagManager tracking={tracking} />
                <Hotjar tracking={tracking} />
                <MetaPixel tracking={tracking} />
                {app}
                <Toaster />
            </TooltipProvider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});

initializeTheme();
