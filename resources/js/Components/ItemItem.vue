<script>
import { router } from '@inertiajs/vue3';

export default {
	props: {
		item: Object,
	},
	data() {
		return {
		};
	},
	methods: {
		openEdit(id) {
			router.get(route('items.edit', id));
		},
	},
	mounted() {
		console.debug('item', this.item);
	},
};
</script>
<template>
	<el-card class="item-item" shadow="hover" @click=openEdit(item.id)>
		<el-descriptions :title="item.title" :column="1">
			<el-descriptions-item>
				<el-text tag="b">Stock Type: </el-text>
				<el-text>
					{{ item.stock_type }}
				</el-text>
			</el-descriptions-item>
			<el-descriptions-item>
				<el-text tag="b">Quantity: </el-text>
				<el-text>{{ item.pieces.length }}</el-text>
				<el-divider direction="vertical"/>
				<el-text tag="b">Events: </el-text>
				<el-text>{{ item.events.length }}</el-text>
			</el-descriptions-item>
		</el-descriptions>
	</el-card>
</template>
<style lang="scss">
.item-item {
	text-align: center;
	height: 120px;
	min-width: 120px;

	white-space: nowrap;
}
</style>