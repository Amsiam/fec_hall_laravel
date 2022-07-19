import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import Layout from "./Shared/Layout"

import 'v-calendar/dist/style.css';
import { SetupCalendar } from 'v-calendar';


createInertiaApp({
    resolve: name => {
        var page = require(`./Pages/${name}`).default;
        if (page.layout === undefined) page.layout = Layout

        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(SetupCalendar, {})
            .mount(el)
    },
})


InertiaProgress.init()
