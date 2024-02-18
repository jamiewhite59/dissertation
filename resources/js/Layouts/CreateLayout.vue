<script>
export default {
	props: {
		existing: Object,
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
	<el-container class="create-container">
		<el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
			<el-row style="height:100%;">
				<slot name="form"></slot>
				<el-row style="width:100%;align-items:flex-end;" justify="space-between">
					<el-col :span="8">
						<el-button v-if="existing" type="danger" @click="$emit('remove')">Delete</el-button>
					</el-col>
					<el-col style="text-align:right;" :span="16">
						<el-button type="primary" @click="$emit('openIndex')">Cancel</el-button>
						<el-button type="primary" @click="$emit('save')">{{existing ? 'Save' : 'Create'}}</el-button>
					</el-col>
				</el-row>
			</el-row>
		</el-col>
		<el-divider class="create-divider" direction="vertical"/>
		<slot></slot>

	</el-container>
</template>
<style lang="scss">
.create-container {
	height: 100%;

	.create-form {
		width: 100%;
	}

	.create-divider {
		height: 100%;

		border-color: #aaa3;
		border-left-width: 2px;

		margin: 0 15px;
	}
}
</style>