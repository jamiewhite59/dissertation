import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import ElementPlus from 'element-plus';
import * as ElementPlusIconsVue from '@element-plus/icons-vue';
import 'element-plus/theme-chalk/src/dark/var.scss';
import '../scss/theme.scss';

import MainLayout from '@/Layouts/MainLayout.vue';
import CustomerItem from '@/Components/CustomerItem.vue';
import EventItem from '@/Components/EventItem.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
	title: (title) => `${appName} - ${title}`,
	resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
	setup({ el, App, props, plugin, }) {
		const app =  createApp({ render: () => h(App, props), });
		app.use(plugin);
		app.use(ElementPlus);
		for (const [key, component,] of Object.entries(ElementPlusIconsVue)) {
			app.component(key, component);
		}
		app.component('MainLayout', MainLayout);
		app.component('CustomerItem', CustomerItem);
		app.component('EventItem', EventItem);

		app.mount(el);
		return app;
	},
	progress: {
		color: '#4B5563',
	},
});
