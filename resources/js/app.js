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
import OverviewLayout from '@/Layouts/OverviewLayout.vue';
import CreateLayout from '@/Layouts/CreateLayout.vue';

import CustomerItem from '@/Components/CustomerItem.vue';
import EventItem from '@/Components/EventItem.vue';
import PieceItem from '@/Components/PieceItem.vue';
import CategoryItem from '@/Components/CategoryItem.vue';
import ItemItem from '@/Components/ItemItem.vue';
import AddCard from '@/Components/AddCard.vue';

import EventForm from '@/Forms/EventForm.vue';
import ItemForm from '@/Forms/ItemForm.vue';
import CustomerForm from '@/Forms/CustomerForm.vue';
import CategoryForm from '@/Forms/CategoryForm.vue';

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
		app.component('OverviewLayout', OverviewLayout);
		app.component('CreateLayout', CreateLayout);
		app.component('CustomerItem', CustomerItem);
		app.component('EventItem', EventItem);
		app.component('PieceItem', PieceItem);
		app.component('CategoryItem', CategoryItem);
		app.component('ItemItem', ItemItem);
		app.component('AddCard', AddCard);
		app.component('EventForm', EventForm);
		app.component('ItemForm', ItemForm);
		app.component('CustomerForm', CustomerForm);
		app.component('CategoryForm', CategoryForm);

		app.mount(el);
		return app;
	},
	progress: {
		color: '#4B5563',
	},
});
