<script>
export default {
	props: {
		addItem: String,
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
	<el-card class="add-card" shadow="hover" @click="$emit('addClicked')">
		<el-text tag="b" size="large">Add {{addItem}}</el-text><el-icon><Plus /></el-icon>
	</el-card>
</template>
<style lang="scss">
.add-card {
	display: flex;
	justify-content: center;
	align-items: center;

	.el-card__body {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;

		gap: 1em;

		.el-descriptions {
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
	}
}
</style>