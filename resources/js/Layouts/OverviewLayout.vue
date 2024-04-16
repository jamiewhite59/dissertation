<script>
export default {
	props: {
		title: String,
		displayCards: Boolean,
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
	<el-container class="overview-container">
		<el-row class="overview-create">
			<el-container>
				<el-button type="primary" @click="$emit('openCreate')">
					<template #icon>
						<el-icon><Plus/></el-icon>
					</template>
					Create
				</el-button>
			</el-container>
			<slot name="extra"></slot>
			<slot name="search"></slot>
		</el-row>
		<slot>
			<el-row class="overview-list">
				<el-scrollbar class="overview-scrollbar" height="100%">
					<slot name="cards" v-if="displayCards"></slot>
					<el-empty v-else :description="'No ' + title +'s'" />
				</el-scrollbar>
			</el-row>
		</slot>
	</el-container>
</template>
<style lang="scss">
.overview-container {
	flex-direction: column;

	height: 100%;

	.overview-create {
		padding-bottom: 20px;

		.overview-search {
			max-width: 25%;
			min-width: 315px;

			padding-left: 1em;
		}
	}

	.overview-list {
		height: calc(100% - 52px);

		.overview-scrollbar {
			width: 100%;
		}
	}
}
</style>