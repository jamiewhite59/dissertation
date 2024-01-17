<script>
import sidebar from '@/Components/sidebar.vue';
import { Head } from '@inertiajs/vue3';

export default {
	components: {
		sidebar,
		Head,
	},
	props: {
		title: String,
	},
};
</script>

<template>
	<Head :title="title" />
	<div class="main-layout">
		<el-container>
			<el-aside width="250px"><sidebar/></el-aside>
			<el-container>
				<el-header class="main-header" height="100px">
					<el-text size="large">{{title}}</el-text>
				</el-header>
				<el-main class="main-main"><slot></slot></el-main>
			</el-container>
		</el-container>
	</div>
</template>
