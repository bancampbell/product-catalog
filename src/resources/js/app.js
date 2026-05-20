import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import axios from 'axios';

// Настройка axios для работы с Sanctum (сессионная аутентификация)
window.axios = axios;
window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
