import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { i18nVue } from 'laravel-vue-i18n';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        // return createApp({ render: () => h(App, props) })
        //     .use(plugin)
        //     .use(i18nVue, {
        //         resolve: async (lang) => {
        //             const langs = import.meta.glob('../../lang/*.json');
        //             return await langs[`../../lang/${lang}.json`]();
        //         }
        //     })
        //     .use(ZiggyVue)
        //     .use(VueSweetalert2),
        //     window.Swal =  app.config.globalProperties.$swal
        //     .mount(el);
        const app =  createApp({ render: () => h(App, props) })
            app.use(plugin)
            app.use(i18nVue, {
                resolve: async (lang) => {
                    const langs = import.meta.glob('../../lang/*.json');
                    return await langs[`../../lang/${lang}.json`]();
                }
            })
            app.use(ZiggyVue, Ziggy)
            app.use(VueSweetalert2),
            window.Swal =  app.config.globalProperties.$swal

            app.mount(el)
    },
    progress: {
        color: '#4B5563',
    },
});
