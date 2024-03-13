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
				<el-text tag="b">Number of Pieces: </el-text>
				<el-text>{{ group.pieces.length }}</el-text>
			</el-descriptions-item>
		</el-descriptions>
	</el-card>
</template>
<style lang="scss">

</style>