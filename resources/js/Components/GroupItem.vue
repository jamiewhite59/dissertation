<script>
import { router } from '@inertiajs/vue3';

export default {
	props: {
		group: Object,
	},
	data() {
		return {
		};
	},
	methods: {
		openEdit(id) {
			router.get(route('groups.edit', id));
		},
	},
};
</script>
<template>
	<el-card shadow="hover" style="text-align:center;" @click="openEdit(group.id)">
		<el-descriptions :title="group.title" :column="1">
			<el-descriptions-item>
				stuff hi
			</el-descriptions-item>
		</el-descriptions>
	</el-card>
</template>
<style lang="scss">

</style>