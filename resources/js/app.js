import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import "../css/app.scss"
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import { aliases, mdi } from "vuetify/lib/iconsets/mdi";
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import "@mdi/font/css/materialdesignicons.css";
import {InertiaProgress} from "@inertiajs/progress"; // Ensure you are using css-loader
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {}
];
const vuetify= createVuetify({
    icons: {
        defaultSet: "mdi",
        aliases,
        sets: {
            mdi,
        },
    },
    components,
    directives,
})
const router = createRouter({
    history: createWebHistory(),
    routes,
});
createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Frontend/Pages/**/*.vue', { eager: true })
        return pages[`./Frontend/Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vuetify)
            .use(ZiggyVue)
            .use(router)
            .mount(el)
    },
}).then(() => {
    InertiaProgress.init({});
})
