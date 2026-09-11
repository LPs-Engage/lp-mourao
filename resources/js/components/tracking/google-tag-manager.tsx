import { router } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import {
    scheduleScriptLoad,
    type ScriptLoadStrategy,
} from '@/components/tracking/schedule-script-load';
import type { TrackingSharedProps } from '@/types/tracking';

declare global {
    interface Window {
        dataLayer?: Record<string, unknown>[];
    }
}

let gtmInitialized = false;
let gtmScriptInserted = false;

function pushVirtualPageView(gtmId: string, pagePath: string, pageTitle: string): void {
    if (!gtmId || !window.dataLayer) {
        return;
    }

    window.dataLayer.push({
        event: 'mourao_virtual_page_view',
        page_path: pagePath,
        page_title: pageTitle,
    });
}

function insertGtmScript(gtmId: string): void {
    if (!gtmId || gtmScriptInserted) {
        return;
    }

    gtmScriptInserted = true;
    window.dataLayer = window.dataLayer ?? [];

    window.dataLayer.push({
        'gtm.start': Date.now(),
        event: 'gtm.js',
    });

    const script = document.createElement('script');
    script.async = true;
    script.src = `https://www.googletagmanager.com/gtm.js?id=${encodeURIComponent(gtmId)}`;
    document.head.appendChild(script);
}

function initializeGtm(gtmId: string, strategy: ScriptLoadStrategy): void {
    if (!gtmId || gtmInitialized) {
        return;
    }

    gtmInitialized = true;
    scheduleScriptLoad(strategy, () => insertGtmScript(gtmId));
}

type GoogleTagManagerProps = {
    tracking?: TrackingSharedProps | null;
};

export default function GoogleTagManager({ tracking }: GoogleTagManagerProps) {
    const gtmId = tracking?.gtmId?.trim() ?? '';
    const loadStrategy = (tracking?.gtmLoadStrategy?.trim() || 'delayed') as ScriptLoadStrategy;
    const lastTrackedPath = useRef<string | null>(null);

    useEffect(() => {
        initializeGtm(gtmId, loadStrategy);
    }, [gtmId, loadStrategy]);

    useEffect(() => {
        if (!gtmId) {
            return;
        }

        const trackNavigation = () => {
            const pagePath = window.location.pathname + window.location.search;
            const pageTitle = document.title;

            if (lastTrackedPath.current === pagePath) {
                return;
            }

            lastTrackedPath.current = pagePath;
            pushVirtualPageView(gtmId, pagePath, pageTitle);
        };

        trackNavigation();

        const removeListener = router.on('navigate', () => {
            window.setTimeout(trackNavigation, 0);
        });

        return () => {
            removeListener();
        };
    }, [gtmId]);

    return null;
}
