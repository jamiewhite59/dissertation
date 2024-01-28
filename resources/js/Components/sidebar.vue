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
		openEvents() {
			router.visit(route('events.index'));
		},
		openCustomers() {
			router.visit(route('customers.index'));
		},
	},
};
</script>

<template>
	<el-menu>
		<el-menu-item index="1" @click="openEvents()">
			<el-icon><Postcard /></el-icon>
			<span>Events</span>
		</el-menu-item>
		<el-menu-item index="2" @click="openCustomers()">
			<el-icon><Coordinate /></el-icon>
			<span>Customers</span>
		</el-menu-item>
		<el-sub-menu index="3" disabled>
			<template #title>
				<el-icon><Document /></el-icon>
				<span>Items</span>
			</template>
			<el-menu-item index="3-1">
				<el-icon><Tickets/></el-icon>
				Items
			</el-menu-item>
			<el-menu-item index="3-2">
				<el-icon><TakeawayBox/></el-icon>
				Categories
			</el-menu-item>
			<el-menu-item index="3-3">
				<el-icon><Folder/></el-icon>
				Groups
			</el-menu-item>
			<el-menu-item index="3-4">
				<el-icon><Flag/></el-icon>
				Post-Event Actions
			</el-menu-item>
			<el-menu-item index="3-5">
				<el-icon><Guide/></el-icon>
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
