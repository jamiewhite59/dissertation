<script>
export default {
	props: {
		categories: Array,
		errors: Object,
		flash: Object,
	},
	data() {
		return {
			search: '',
			createDialogVisible: false,
		};
	},
	computed: {
		searchedCategories() {
			if (this.search) {
				return this.categories.filter((category) => {
					return category.title.toLowerCase().includes(this.search.toLowerCase())
					|| category.description?.toLowerCase().includes(this.search.toLowerCase());
				});
			} else {
				return this.categories;
			}
		},
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
	<MainLayout title="Categories" :errors="errors" :flash="flash">
		<OverviewLayout title="Category" :displayCards="!!categories.length" @openCreate="openCreate">
			<template #search>
				<el-input class="overview-search" v-model="search" placeholder="Search Categories" clearable />
			</template>
			<template #cards>
				<el-container class="category-list-space">
					<CategoryItem v-for="category in searchedCategories" :key="category.id" :category="category" />
				</el-container>
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
.category-list-space {
	display: grid !important;
	grid-gap: 15px;
	grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
}
</style>