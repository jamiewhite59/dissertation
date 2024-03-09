<script>
import { router } from '@inertiajs/vue3';
export default {
	props: {
		categories: Array,
	},
	data() {
		return {
			search: '',
			createDialogVisible: false,
		};
	},
	methods: {
		createCategory() {
			this.$refs.categoryForm.save();
			this.createDialogVisible = false;
		},
		resetForm() {
			this.$refs.categoryForm.resetForm();
		},
		openCreate() {
			this.createDialogVisible = true;
		},
	},
};
</script>
<template>
	<MainLayout title="Categories">
		<OverviewLayout @openCreate="openCreate">
			<template #search>
				<el-input class="overview-search" v-model="search" placeholder="Search Categories" clearable />
			</template>
			<template #default>
				hello
				<!-- <el-container class="item-table-container">
					<el-table :data="filteredItems" height="100%" @row-click="openEdit">
						<el-table-column prop="title" label="Title" sortable/>
						<el-table-column prop="stock_type" label="Stock Type" sortable/>
						<el-table-column prop="quantity" label="Quantity" />
						<el-table-column prop="description" label="Description"/>
					</el-table>
				</el-container> -->
			</template>
		</OverviewLayout>
	</MainLayout>
	<el-dialog v-model="createDialogVisible" width="30%" align-center @closed="resetForm">
		<template #header>Create Category</template>
		<template #default>
			<CategoryForm ref="categoryForm"/>
		</template>
		<template #footer>
			<el-button type="primary" @click="createDialogVisible = false">Cancel</el-button>
			<el-button type="primary" @click="createCategory">Create</el-button>
		</template>
	</el-dialog>
</template>
<style lang="scss">

</style>