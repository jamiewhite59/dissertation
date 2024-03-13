<script>
import { router } from '@inertiajs/vue3';

export default {
	computed: {
		activeMenuItem() {
			let page = router.page.url.split('/')[1];
			switch (page) {
			case 'events':
				return '1';
			case 'customers':
				return '2';
			case 'items':
				return '3-1';
			case 'pieces':
				return '3-2';
			case 'categories':
				return '3-3';
			case 'groups':
				return '3-4';
			case 'post-event-actions':
				return '3-5';
			case 'periodic-actions':
				return '3-6';
			case 'organisation':
				return '4';
			case 'logs':
				return '5';
			case 'user':
				return '6';
			case 'analytics':
				return '7';
			default:
				return '0';
			}
		},
		activeSubMenu() {
			let arr = this.activeMenuItem.split('-');
			if (arr.length > 1) {
				return [arr[0],];
			} else {
				return null;
			}
		},
	},
	methods: {
		openIndex(name) {
			router.visit(route(name));
		},
	},
};
</script>

<template>
	<el-menu :default-active="activeMenuItem" :default-openeds="activeSubMenu">
		<el-menu-item index="1" @click="openIndex('events.index')">
			<el-icon><Postcard /></el-icon>
			<span>Events</span>
		</el-menu-item>
		<el-menu-item index="2" @click="openIndex('customers.index')">
			<el-icon><Coordinate /></el-icon>
			<span>Customers</span>
		</el-menu-item>
		<el-sub-menu index="3">
			<template #title>
				<el-icon><Document /></el-icon>
				<span>Items</span>
			</template>
			<el-menu-item index="3-1" @click="openIndex('items.index')">
				<el-icon><Tickets /></el-icon>
				Items
			</el-menu-item>
			<el-menu-item index="3-2" disabled>
				<el-icon><Postcard /></el-icon>
				Pieces
			</el-menu-item>
			<el-menu-item index="3-3" @click="openIndex('categories.index')">
				<el-icon><TakeawayBox /></el-icon>
				Categories
			</el-menu-item>
			<el-menu-item index="3-4" @click="openIndex('groups.index')">
				<el-icon><Folder /></el-icon>
				Groups
			</el-menu-item>
			<el-menu-item index="3-5" disabled>
				<el-icon><Flag /></el-icon>
				Post-Event Actions
			</el-menu-item>
			<el-menu-item index="3-6" disabled>
				<el-icon><Guide /></el-icon>
				Periodic Actions
			</el-menu-item>
		</el-sub-menu>
		<el-menu-item index="4" disabled>
			<el-icon><OfficeBuilding /></el-icon>
			<span>Organisation</span>
		</el-menu-item>
		<el-menu-item index="5" disabled>
			<el-icon><Files /></el-icon>
			<span>Logs</span>
		</el-menu-item>
		<el-menu-item index="6" disabled>
			<el-icon><User /></el-icon>
			<span>User</span>
		</el-menu-item>
		<el-menu-item index="7" disabled>
			<el-icon><Histogram /></el-icon>
			<span>Analytics</span>
		</el-menu-item>
	</el-menu>
</template>
