<script>
import { router } from '@inertiajs/vue3';

export default {
	components: {

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
			<!-- <el-icon><Postcard /></el-icon> -->
			<span>Events</span>
		</el-menu-item>
		<el-menu-item index="2" @click="openCustomers()">
			<!-- <el-icon><Coordinate /></el-icon> -->
			<span>Customers</span>
		</el-menu-item>
		<el-menu-item index="3" disabled>
			<!-- <el-icon><Document /></el-icon> -->
			<span>Items</span>
		</el-menu-item>
		<el-menu-item index="4" disabled>
			<!-- <el-icon><OfficeBuilding /></el-icon> -->
			<span>Organisation</span>
		</el-menu-item>
		<el-menu-item index="5" disabled>
			<!-- <el-icon><Files /></el-icon> -->
			<span>Logs</span>
		</el-menu-item>
		<el-menu-item index="6" disabled>
			<!-- <el-icon><User /></el-icon> -->
			<span>User</span>
		</el-menu-item>
	</el-menu>
</template>
