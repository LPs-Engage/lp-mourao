import { router } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import {
    scheduleScriptLoad,
    type ScriptLoadStrategy,
} from '@/components/tracking/schedule-script-load';
import type { TrackingSharedProps } from '@/types/tracking';

type FbqFunction = {
    (...args: unknown[]): void;
    callMethod?: (...args: unknown[]) => void;
    queue: unknown[];
    loaded?: boolean;
    version?: string;
    push: (...args: unknown[]) => void;
};

declare global {
    interface Window {
        fbq?: FbqFunction;
        _fbq?: FbqFunction;
    }
}

let pixelInitialized = false;
let pixelScriptInserted = false;

function insertMetaPixelScript(pixelId: string): void {
    if (!pixelId || pixelScriptInserted) {
        return;
    }

    pixelScriptInserted = true;

    const fbq: FbqFunction = function (...args: unknown[]) {
        if (fbq.callMethod) {
            fbq.callMethod(...args);
        } else {
            fbq.queue.push(args);
        }
    } as FbqFunction;

    fbq.push = fbq;
    fbq.loaded = true;
    fbq.version = '2.0';
    fbq.queue = [];

    window.fbq = fbq;
    window._fbq = fbq;

    const script = document.createElement('script');
    script.async = true;
    script.src = 'https://connect.facebook.net/en_US/fbevents.js';
    document.head.appendChild(script);

    window.fbq('init', pixelId);
    window.fbq('track', 'PageView');
}

function initializeMetaPixel(pixelId: string, strategy: ScriptLoadStrategy): void {
    if (!pixelId || pixelInitialized) {
        return;
    }

    pixelInitialized = true;
    scheduleScriptLoad(strategy, () => insertMetaPixelScript(pixelId));
}

function trackPageView(): void {
    if (typeof window.fbq !== 'function') {
        return;
    }

    window.fbq('track', 'PageView');
}

type MetaPixelProps = {
    tracking?: TrackingSharedProps | null;
};

export default function MetaPixel({ tracking }: MetaPixelProps) {
    const pixelId = tracking?.metaPixelId?.trim() ?? '';
    const loadStrategy = (tracking?.metaPixelLoadStrategy?.trim() ||
        'delayed') as ScriptLoadStrategy;

    const lastTrackedPath = useRef<string | null>(null);
    const isFirstNavigation = useRef(true);

    useEffect(() => {
        initializeMetaPixel(pixelId, loadStrategy);
    }, [pixelId, loadStrategy]);

    useEffect(() => {
        if (!pixelId) {
            return;
        }

        const trackNavigation = () => {
            const pagePath = window.location.pathname + window.location.search;

            if (lastTrackedPath.current === pagePath) {
                return;
            }

            lastTrackedPath.current = pagePath;

            if (isFirstNavigation.current) {
                isFirstNavigation.current = false;
                return;
            }

            trackPageView();
        };

        trackNavigation();

        const removeListener = router.on('navigate', () => {
            window.setTimeout(trackNavigation, 0);
        });

        return () => {
            removeListener();
        };
    }, [pixelId]);

    return null;
}
