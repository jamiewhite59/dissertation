<script>
export default {
	components: {

	},
	methods: {

	},
};
</script>

<template>
	<div class="sidebar">
		<el-link :href="route('customers.index')">Customers</el-link>
		<el-link :href="route('events.index')">Events</el-link>
	</div>
	<!-- <el-menu>
		<el-menu-item index="1" @click="route('customers')">
			<el-icon><Postcard /></el-icon>
			<span>Events</span>
		</el-menu-item>
		<el-menu-item index="2" @click="customers()">
			<el-icon><Coordinate /></el-icon>
			<span>Customers</span>
		</el-menu-item>
		<el-menu-item index="3" disabled>
			<el-icon><Document /></el-icon>
			<span>Items</span>
		</el-menu-item>
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
	</el-menu> -->
</template>
