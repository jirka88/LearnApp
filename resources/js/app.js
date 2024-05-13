import {createApp, h} from 'vue'
import {createInertiaApp} from '@inertiajs/inertia-vue3'
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m'
import "../css/app.scss"
import 'vuetify/styles'
import {createVuetify} from 'vuetify'
import {aliases, mdi} from "vuetify/iconsets/mdi";
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import "@mdi/font/css/materialdesignicons.css";
import {InertiaProgress} from "@inertiajs/progress"; // Ensure you are using css-loader
import {createRouter, createWebHistory} from 'vue-router';
import AOS from 'aos'
import 'aos/dist/aos.css'
import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3'

const page = usePage()
import {VueReCaptcha, useReCaptcha} from 'vue-recaptcha-v3'

const accentColor = computed(() => {
    if (page.props) {
        return page.props.settings.theme.color;
    }
    return '#4398f0'
});

const routes = [
    {}
];

import {i18nVue} from 'laravel-vue-i18n'
import setLanguage from "./setLanguage";

let light = {
    variables: {},
    colors: {
        accentCustom: accentColor,
    }
};
const vuetify = createVuetify({
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
            light: light
        }
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
        const pages = import.meta.glob('./Frontend/Pages/**/*.vue', {eager: true})
        return pages[`./Frontend/Pages/${name}.vue`]
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
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
            .use(VueReCaptcha, {
                siteKey: '6LdpIXMpAAAAAHM1GBYAPAv884fSC7_hCwx8JPW9',
                loaderOptions: {autoHideBadge: true, useRecaptchaNet: true}
            })
            .mount(el)
    },
}).then(() => {
    AOS.init();
    InertiaProgress.init({});
})
