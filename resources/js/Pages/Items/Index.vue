<script>
import { router } from '@inertiajs/vue3';

export default {
	props: {
		items: Array,
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
		filteredItems() {
			if (this.search) {
				return this.items.filter((item) => {
					let search = this.search.toLowerCase();
					let title = item.title ? item.title.toLowerCase().includes(search) : false;
					let description = item.description ? item.description.toLowerCase().includes(search) : false;
					let stock_type = item.stock_type ? item.stock_type.toLowerCase().includes(search) : false;
					return title || description || stock_type;
				});
			} else {
				return this.items;
			}
		},
	},
	methods: {
		openCreate() {
			this.createDialogVisible = true;
		},
		openEdit(item) {
			router.get(route('items.edit', item.id));
		},
		createItem() {
			this.$refs.itemForm.save()
				.then(() => {
					this.createDialogVisible = false;
				});
		},
		resetForm() {
			this.$refs.itemForm.resetForm();
		},
	},
};
</script>
<template>
	<MainLayout title="Items" :errors="errors" :flash="flash">
		<OverviewLayout @openCreate="openCreate">
			<template #search>
				<el-input class="overview-search" v-model="search" placeholder="Search Items" clearable />
			</template>
			<template #default>
				<el-container class="item-table-container">
					<el-table :data="filteredItems" height="100%" :default-sort="{prop: 'category.title', order:'ascending'}" @row-click="openEdit">
						<el-table-column prop="title" label="Title" sortable/>
						<el-table-column prop="category.title" label="Category" sortable />
						<el-table-column prop="stock_type" label="Stock Type" sortable/>
						<el-table-column prop="quantity" label="Quantity" />
						<el-table-column prop="description" label="Description"/>
					</el-table>
				</el-container>
			</template>
		</OverviewLayout>
	</MainLayout>
	<el-dialog v-model="createDialogVisible" width="30%" style="min-height: 400px" align-center @opened="$refs.itemForm.$refs.itemTitle.focus()" @closed="resetForm">
		<template #header>Create Item</template>
		<template #default>
			<ItemForm ref="itemForm" :categories="categories" @keyup.enter="createItem" />
		</template>
		<template #footer>
			<el-button type="primary" @click="createDialogVisible = false">Cancel</el-button>
			<el-button type="primary" @click="createItem">Create</el-button>
		</template>
	</el-dialog>
</template>
<style lang="scss">
.item-table-container {
	height: 100%;

	overflow: hidden;
}
</style>