<script>
export default {
	props: {
		piece: Object,
		showTitle: Boolean,
		indicateGroup: Boolean,
	},
	data() {
		return {
		};
	},
	methods: {
	},
};
</script>
<template>
	<el-card class="piece-item" shadow="hover">
		<el-descriptions :title="piece.code" :column="1">
			<template #extra>
				<el-button size="small" @click.stop="$emit('removePiece', piece.id)"><el-icon><Minus/></el-icon></el-button>
			</template>
			<el-descriptions-item v-if="showTitle">
				<el-text>{{ piece.item.title }}</el-text>
			</el-descriptions-item>
			<el-descriptions-item v-else-if="indicateGroup">
				<template v-if="piece.group_id">
					<el-text tag="b">Group: </el-text>
					<el-text>{{ piece.group.title }}</el-text>
				</template>
			</el-descriptions-item>
		</el-descriptions>
	</el-card>
</template>
<style lang="scss">

.piece-item {
	text-align: center;
	height: 80px;
	min-width: 120px;

	white-space: nowrap;
}
</style>