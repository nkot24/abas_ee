import '../css/app.css';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { LanguageProvider } from './i18n';

createInertiaApp({
    title: (title) => {
        const siteName = import.meta.env.VITE_APP_NAME ?? 'Laravel';
        return title ? `${title} - ${siteName}` : siteName;
    },
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.jsx`,
            import.meta.glob('./Pages/**/*.jsx')
        ),
    setup({ el, App, props }) {
        createRoot(el).render(
            <LanguageProvider>
                <App {...props} />
            </LanguageProvider>
        );
    },
});
