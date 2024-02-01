import { createInertiaApp } from '@inertiajs/vue3'
import createServer from '@inertiajs/vue3/server'
import { renderToString } from '@vue/server-renderer'
import { createSSRApp, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import "../css/app.scss"
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import { aliases, mdi } from "vuetify/iconsets/mdi";
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import "@mdi/font/css/materialdesignicons.css";
import {InertiaProgress} from "@inertiajs/progress"; // Ensure you are using css-loader
import AOS from 'aos'
import 'aos/dist/aos.css'
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3'
const page = usePage()

const accentColor = computed(() => {
    console.log(page.props)
    if(page.props) {
        return page.props.settings.theme.color;
    }
    return '#4398f0'
});

import { i18nVue } from 'laravel-vue-i18n'

const vuetify= createVuetify({
    icons: {
        defaultSet: "mdi",
        aliases,
        sets: {
            mdi,
        },
    },
    theme: {
        options: {
            customProperties: true
        },
        themes: {
            light: {
                variables: {},
                colors: {
                    accentCustom: accentColor,
                }
            }
        }
    },
    components,
    directives,
})
createServer((page) =>
    createInertiaApp({
    render: renderToString,
    resolve: name => {
        const pages = import.meta.glob('./Frontend/Pages/**/*.vue', { eager: true })
        return pages[`./Frontend/Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
     createSSRApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vuetify)
            .use(ZiggyVue)
            .use(i18nVue, {
                lang: 'cs',
                fallbackLang: 'en',
                resolve: async lang => {
                    const langs = import.meta.glob('../../lang/*.json');
                    return await langs[`../../lang/${lang}.json`]();
                }
            })
            .mount(el)
    },
}).then(() => {
    AOS.init();
    InertiaProgress.init({});
})
);
