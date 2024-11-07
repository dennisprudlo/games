import '../css/app.css';

import axios from 'axios';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/animations/shift-away.css';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy';
import { Ziggy } from './ziggy.js';
import { Application } from '@/Layouts';
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
import { createPinia } from 'pinia'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        const page = pages[`./Pages/${name}.vue`];

        if (page.default.layout === undefined) {
            page.default.layout ??= Application;
        }

        return page;
    },
    setup({ el, App, props, plugin }) {

        //
        // Intercept responses and set the locale
        axios.interceptors.response.use(response => {
            if (response.data && response.data.props && response.data.props.locale) {
                window.Lang.setLocale(response.data.props.locale);
            }

            return response;
        }, error => Promise.reject(error));

        const pinia = createPinia();

        const app = createApp({ render: () => h(App, props) })
            .use(pinia)
            .use(plugin)
            .use(ZiggyVue, Ziggy);

        window.tippy = tippy;
        tippy.setDefaultProps({
            allowHTML: false,
            animation: 'shift-away',
            appendTo: () => document.body,
            arrow: false,
            ignoreAttributes: true,
            inertia: true,
            interactive: true,
            maxWidth: 400,
            offset: [0, 12],
            placement: 'top',
            theme: 'games',
        });

        const locale = document.querySelector('html')?.getAttribute('lang') || 'en';
        window.Lang.setLocale(locale);

        app.config.globalProperties.$t = (...args) => window.Lang.get(...args);
        app.config.globalProperties.$choice = (...args) => window.Lang.choice(...args);

        app.config.globalProperties.$filters = {
            escape (value) {
                const div = document.createElement('div');
                div.innerText = value;
                return div.innerHTML;
            },
        }

        // window.Pusher = Pusher;
        // window.Echo = new Echo({
        //     broadcaster: 'reverb',
        //     key: import.meta.env.VITE_REVERB_APP_KEY,
        //     wsHost: import.meta.env.VITE_REVERB_HOST,
        //     wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        //     wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        //     forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        //     enabledTransports: ['ws', 'wss'],
        // });

        app.mount(el);
    },
    progress: {
        delay: 100,
        color: '#2A5C52',
        includeCSS: true,
        showSpinner: true,
    },
})
