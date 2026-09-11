import type { ScriptLoadStrategy } from '@/components/tracking/schedule-script-load';

export type TrackingSharedProps = {
    gtmId: string;
    gtmLoadStrategy: ScriptLoadStrategy;
    hotjarId: string;
    hotjarLoadStrategy: ScriptLoadStrategy;
    metaPixelId: string;
    metaPixelLoadStrategy: ScriptLoadStrategy;
};
