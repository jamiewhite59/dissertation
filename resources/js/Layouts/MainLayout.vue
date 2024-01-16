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
	}
}
</script>

<template>
	<Head :title="title" />
	<div class="main-layout">
	  <el-container>
		<el-aside width="250px"><sidebar></sidebar></el-aside>
		<el-container>
		  <el-header>{{title}}</el-header>
		  <el-main><slot></slot></el-main>
		</el-container>
	  </el-container>
	</div>
  </template>
