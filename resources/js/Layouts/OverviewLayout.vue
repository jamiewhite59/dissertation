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
		<el-header class="overview-create">
			<el-container>
				<el-button type="primary" @click="$emit('openCreate')">
					<template #icon>
						<el-icon><Plus/></el-icon>
					</template>
					Create
				</el-button>
			</el-container>
			<slot name="search"></slot>
		</el-header>
		<slot>
			<el-main class="overview-list">
				<slot name="cards" v-if="displayCards"></slot>
				<el-empty v-else :description="'No' + title +'s'" />
			</el-main>
		</slot>
	</el-container>
</template>
<style lang="scss">
.overview-container {
	height: 100%;
}

.overview-create {
	display: flex;
	justify-content: flex-end;
	align-items: center;
	gap: 1em;
	height: auto;

	padding-bottom: 20px;

	.overview-search {
		max-width: 450px;

		padding-left: 1em;
	}
}

.overview-list {
	flex: initial !important;

	padding-top: 0;

}
</style>