<script>
import { router } from '@inertiajs/vue3';
import { Postcard, Coordinate, Document, OfficeBuilding, Files, User } from '@element-plus/icons-vue';

export default {
	components: {
		Postcard,
		Coordinate,
		Document,
		OfficeBuilding,
		Files,
		User,
	},
	methods: {
		openIndex(name) {
			router.visit(route(name));
		},
	},
};
</script>

<template>
	<el-menu>
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
				<el-icon><TakeawayBox /></el-icon>
				Categories
			</el-menu-item>
			<el-menu-item index="3-3" disabled>
				<el-icon><Folder /></el-icon>
				Groups
			</el-menu-item>
			<el-menu-item index="3-4" disabled>
				<el-icon><Flag /></el-icon>
				Post-Event Actions
			</el-menu-item>
			<el-menu-item index="3-5" disabled>
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
	</el-menu>
</template>
