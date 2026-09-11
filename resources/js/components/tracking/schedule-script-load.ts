export type ScriptLoadStrategy = 'immediate' | 'delayed' | 'idle' | 'afterInteraction';

/**
 * Agenda a inserção de scripts de tracking fora do caminho crítico.
 * Default recomendado: delayed (2s) ou idle.
 */
export function scheduleScriptLoad(strategy: ScriptLoadStrategy, onLoad: () => void): void {
    switch (strategy) {
        case 'immediate':
            onLoad();
            break;
        case 'delayed':
            window.setTimeout(onLoad, 2000);
            break;
        case 'idle':
            if (typeof window.requestIdleCallback === 'function') {
                window.requestIdleCallback(() => onLoad(), { timeout: 3000 });
            } else {
                window.setTimeout(onLoad, 2000);
            }

            break;
        case 'afterInteraction': {
            const handler = () => {
                onLoad();
                window.removeEventListener('pointerdown', handler);
                window.removeEventListener('keydown', handler);
                window.removeEventListener('scroll', handler);
            };
            window.addEventListener('pointerdown', handler, { once: true });
            window.addEventListener('keydown', handler, { once: true });
            window.addEventListener('scroll', handler, { once: true, passive: true });
            break;
        }
        default:
            window.setTimeout(onLoad, 2000);
    }
}
