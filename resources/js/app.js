import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import ElementPlus from 'element-plus';
import * as ElementPlusIconsVue from '@element-plus/icons-vue';
import 'element-plus/theme-chalk/src/dark/var.scss';
import '../scss/theme.scss';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
	title: (title) => `${appName} - ${title}`,
	resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
	setup({ el, App, props, plugin, }) {
		const app =  createApp({ render: () => h(App, props), })
			.use(plugin)
			.use(ElementPlus)
			.mount(el);

		for (const [key, component,] of Object.entries(ElementPlusIconsVue)) {
			app.component(key, component);
		}

		return app;
	},
	progress: {
		color: '#4B5563',
	},
});
