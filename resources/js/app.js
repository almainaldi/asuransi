import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

// === TAMBAHAN POINT A: STATE MANAGEMENT (PINIA) ===
import { createPinia } from 'pinia';

// === TAMBAHAN POINT B: UI LIBRARY (PRIMEVUE) ===
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        // Membuat instance Pinia
        const pinia = createPinia();

        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(pinia) // Menggunakan Pinia
            .use(PrimeVue, {
                // Mengonfigurasi PrimeVue agar menggunakan tema Aura (Kompatibel dengan Tailwind)
                theme: {
                    preset: Aura
                }
            })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });