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
	computed: {
		groupItems() {
			let items = [];
			this.group.pieces.forEach((piece) => {
				let item = items.find((item) => item.id === piece.item_id);
				if (! item) {
					piece.item.quantity = 1;
					items.push(piece.item);
				} else {
					item.quantity ++;
				}
			});
			return items;
		},
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
			<template #extra>
				<el-popover placement="bottom" trigger="hover" width="300">
					<template #reference>
						<el-icon><InfoFilled /></el-icon>
					</template>
					<el-descriptions direction="vertical" :column="1" size="small">
						<el-descriptions-item v-for="item in groupItems" :key="item.id">
							<el-text>{{ item.title }} ({{ item.quantity }})</el-text>
						</el-descriptions-item>
					</el-descriptions>
				</el-popover>
			</template>
			<el-descriptions-item>
				<el-text tag="b">Number of Pieces: </el-text>
				<el-text>{{ group.pieces.length }}</el-text>
			</el-descriptions-item>
		</el-descriptions>
	</el-card>
</template>
<style lang="scss">

</style>