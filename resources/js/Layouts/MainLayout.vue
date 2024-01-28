<script>
import Sidebar from '@/Components/Sidebar.vue';
import { Head } from '@inertiajs/vue3';
import { UserFilled } from '@element-plus/icons-vue';

export default {
	components: {
		Sidebar,
		Head,
		UserFilled,
	},
	props: {
		title: String,
	},
};
</script>

<template>
	<Head :title="title" />
	<div class="main-layout">
		<el-container class="main-container">
			<el-aside class="main-menu" width="250px">
				<div class="logo-container">
					<img class="logo" src="../../logo.png">
				</div>
				<sidebar/>
			</el-aside>
			<el-container class="main-content">
				<el-header class="main-header" height="100px">
					<el-text class="page-title" size="large" tag="b">{{title}}</el-text>
					<el-avatar class="user-avatar">
						<el-icon><UserFilled/></el-icon>
					</el-avatar>
				</el-header>
				<el-main class="main-main"><slot></slot></el-main>
			</el-container>
		</el-container>
	</div>
</template>

<style scoped>
	.page-title {
		color: var(--el-color-black);
		font-size: 2em;
	}
</style>