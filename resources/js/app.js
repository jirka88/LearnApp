import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy';

import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import { aliases, mdi } from "vuetify/lib/iconsets/mdi";
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import "@mdi/font/css/materialdesignicons.css"; // Ensure you are using css-loader
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
            .mount(el)
    },
})
