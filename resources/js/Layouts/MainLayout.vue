<script>
import sidebar from '@/Components/sidebar.vue';

export default {
	components: {
		sidebar,
	},
	props: {
		title: String,
	}
}
</script>

<template>
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
