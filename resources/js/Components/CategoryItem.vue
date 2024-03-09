<script>
import { router } from '@inertiajs/vue3';

export default {
	props: {
		category: Object,
	},
	data() {
		return {
		};
	},
	methods: {
		openEdit(id) {
			router.get(route('categories.edit', id));
		},
	},
};
</script>
<template>
	<el-card shadow="hover" style="text-align:center;" @click="openEdit(category.id)">
		<el-descriptions :title="category.title" :column="1">
			<el-descriptions-item>
				<span>{{ category.description }}</span>
			</el-descriptions-item>
		</el-descriptions>
	</el-card>
</template>
<style lang="scss">

</style>