<script>
export default {
	props: {
		piece: Object,
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