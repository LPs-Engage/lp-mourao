import { createInertiaApp } from '@inertiajs/react';
import createServer from '@inertiajs/react/server';
import type { ComponentType } from 'react';
import ReactDOMServer from 'react-dom/server';
import { resolvePageLayout } from '@/layout-resolver';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const pages = import.meta.glob<{ default: ComponentType }>('./pages/**/*.tsx', {
    eager: true,
});

createServer((page) =>
    createInertiaApp({
        page,
        render: ReactDOMServer.renderToString,
        title: (title) => title || appName,
        resolve: (name) => {
            const pageModule = pages[`./pages/${name}.tsx`];

            if (!pageModule) {
                throw new Error(`Page not found: ${name}`);
            }

            return pageModule;
        },
        layout: resolvePageLayout,
        setup: ({ App, props }) => <App {...props} />,
    }),
);
