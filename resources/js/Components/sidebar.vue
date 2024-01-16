<script>
import {Setting, User, Postcard, Coordinate, Document, OfficeBuilding, Files} from '@element-plus/icons-vue';

export default {
components: {
	User,
	Postcard,
	Coordinate,
	Document,
	OfficeBuilding,
	Files,
}
}
</script>

<template>
	<el-menu>
		<el-menu-item index="1">
			<el-icon><Postcard /></el-icon>
			<span>Events</span>
		</el-menu-item>
		<el-menu-item index="2">
			<el-icon><Coordinate /></el-icon>
			<span>Customers</span>
		</el-menu-item>
		<el-menu-item index="3">
			<el-icon><Document /></el-icon>
			<span>Items</span>
		</el-menu-item>
		<el-menu-item index="4">
			<el-icon><OfficeBuilding /></el-icon>
			<span>Organisation</span>
		</el-menu-item>
		<el-menu-item index="5">
			<el-icon><Files /></el-icon>
			<span>Logs</span>
		</el-menu-item>
		<el-menu-item index="6">
			<el-icon><User /></el-icon>
			<span>User</span>
		</el-menu-item>
	</el-menu>
</template>
