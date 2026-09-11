import { router } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import {
    scheduleScriptLoad,
    type ScriptLoadStrategy,
} from '@/components/tracking/schedule-script-load';
import type { TrackingSharedProps } from '@/types/tracking';

type HjFunction = {
    (...args: unknown[]): void;
    q?: unknown[];
};

declare global {
    interface Window {
        hj?: HjFunction;
        _hjSettings?: { hjid: number };
    }
}

let hotjarInitialized = false;
let hotjarScriptInserted = false;

function insertHotjarScript(hotjarId: string): void {
    if (!hotjarId || hotjarScriptInserted) {
        return;
    }

    const hjid = Number(hotjarId);

    if (!Number.isFinite(hjid) || hjid <= 0) {
        return;
    }

    hotjarScriptInserted = true;

    const hj: HjFunction = function (...args: unknown[]) {
        (hj.q = hj.q || []).push(args);
    };
    window.hj = hj;
    window._hjSettings = { hjid };

    const script = document.createElement('script');
    script.async = true;
    script.src = `https://static.hj.contentsquare.net/c/csq-${hjid}.js`;
    document.head.appendChild(script);
}

function initializeHotjar(hotjarId: string, strategy: ScriptLoadStrategy): void {
    if (!hotjarId || hotjarInitialized) {
        return;
    }

    hotjarInitialized = true;
    scheduleScriptLoad(strategy, () => insertHotjarScript(hotjarId));
}

function notifyHotjarStateChange(pagePath: string): void {
    if (typeof window.hj !== 'function') {
        return;
    }

    window.hj('stateChange', pagePath);
}

type HotjarProps = {
    tracking?: TrackingSharedProps | null;
};

export default function Hotjar({ tracking }: HotjarProps) {
    const hotjarId = tracking?.hotjarId?.trim() ?? '';
    const loadStrategy = (tracking?.hotjarLoadStrategy?.trim() || 'idle') as ScriptLoadStrategy;
    const lastTrackedPath = useRef<string | null>(null);

    useEffect(() => {
        initializeHotjar(hotjarId, loadStrategy);
    }, [hotjarId, loadStrategy]);

    useEffect(() => {
        if (!hotjarId) {
            return;
        }

        const trackNavigation = () => {
            const pagePath = window.location.pathname + window.location.search;

            if (lastTrackedPath.current === pagePath) {
                return;
            }

            lastTrackedPath.current = pagePath;
            notifyHotjarStateChange(pagePath);
        };

        trackNavigation();

        const removeListener = router.on('navigate', () => {
            window.setTimeout(trackNavigation, 0);
        });

        return () => {
            removeListener();
        };
    }, [hotjarId]);

    return null;
}
