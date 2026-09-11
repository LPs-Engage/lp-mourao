import { router } from '@inertiajs/react';
import { createContext, useContext, useEffect, useState } from 'react';

type PageMotionPhase = 'enter-from-left' | 'visible' | 'exit-to-right';

type PageMotionContextValue = {
    phase: PageMotionPhase;
};

const PageMotionContext = createContext<PageMotionContextValue>({
    phase: 'enter-from-left',
});

export function usePageMotion(): PageMotionContextValue {
    return useContext(PageMotionContext);
}

export function PageMotionProvider({ children }: { children: React.ReactNode }) {
    const [phase, setPhase] = useState<PageMotionPhase>('enter-from-left');

    useEffect(() => {
        const enterFrame = requestAnimationFrame(() => setPhase('visible'));

        const removeStart = router.on('start', () => setPhase('exit-to-right'));
        const removeFinish = router.on('finish', () => {
            setPhase('enter-from-left');
            requestAnimationFrame(() => setPhase('visible'));
        });

        return () => {
            cancelAnimationFrame(enterFrame);
            removeStart();
            removeFinish();
        };
    }, []);

    return (
        <PageMotionContext.Provider value={{ phase }}>{children}</PageMotionContext.Provider>
    );
}
