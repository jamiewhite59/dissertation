<script>
import { router } from '@inertiajs/vue3';

import { ElMessageBox } from 'element-plus';

export default {
	props: {
		category: Object,
		errors: Object,
		flash: Object,
		items: Array,
	},
	data() {
		return {
			formChanges: false,
			itemDialogVisible: false,
			itemSearch: '',
		};
	},
	computed: {
		filteredItems() {
			var categoryItemIds = this.category.items.map((item) => item.id);
			var availableItems = this.items.filter((item) => ! categoryItemIds.includes(item.id));
			if (this.itemSearch) {
				return availableItems.filter((item) => {
					return item.title.toLowerCase().includes(this.itemSearch.toLowerCase());
				});
			} else {
				return availableItems;
			}
		},
	},
	methods: {
		remove() {
			ElMessageBox.confirm(
				'Are you sure you want to permanently delete this category?',
				'Delete Category',
				{
					confirmButtonText: 'Delete',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.delete(route('categories.destroy', this.category.id));
			}).catch(() => {});
		},
		openIndex() {
			router.get(route('categories.index'));
		},
		save() {
			this.$refs.categoryForm.save();
		},
		addItem(item) {
			if (item.category_id) {
				ElMessageBox.confirm(
					'This item is already assigned to a category, would you like to update it?',
					'Update Category',
					{
						confirmButtonText: 'Update',
						type: 'error',
						center: true,
					}
				).then(() => {
					router.put(route('categories.addItem', this.category.id), {id: item.id,});
				}).catch(() => {});
			} else {
				router.put(route('categories.addItem', this.category.id), {id: item.id,});
			}
		},
		removeItem(id) {
			ElMessageBox.confirm(
				'Are you sure you want to remove this item from this category?',
				'Remove Item',
				{
					confirmButtonText: 'Remove',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.put(route('categories.removeItem', this.category.id), {id: id,});
			}).catch(() => {});
		},
	},
};
</script>
<template>
	<MainLayout :title="category ? category.title : 'Category'" :errors="errors" :flash="flash">
		<CreateLayout :existing="category" :changes="formChanges" @remove="remove" @openIndex="openIndex" @save="save">
			<template #form>
				<el-container direction="vertical">
					<el-text size="large" tag="b">Details</el-text>
					<CategoryForm ref="categoryForm" :category="category" @change="(e) => typeof(e) === 'boolean' ? formChanges=e : ''" />
				</el-container>
			</template>
			<template #default>
				<el-col class="items-content">
					<el-text size="large" tag="b">Items ({{ category.items.length }})</el-text>
					<el-container class="item-wrapper">
						<el-scrollbar class="piece-scrollbar" height="100%">
							<el-container class="list-space">
								<AddCard class="item-item" addItem="Item" @addClicked="itemDialogVisible = true"/>
								<ItemItem v-for="item in category.items" :key="item.id" :item="item" remove @removeItem="removeItem" />
							</el-container>
						</el-scrollbar>
					</el-container>
				</el-col>
			</template>
		</CreateLayout>
	</MainLayout>
	<el-dialog v-model="itemDialogVisible" width="30%" style="min-height:400px;" align-center @opened="$refs.itemSearch.focus()" @closed="itemSearch = ''">
		<template #header>Items</template>
		<template #default>
			<el-input class="dialog-search" v-model="itemSearch" ref="itemSearch" placeholder="Search Items" clearable />
			<el-table :data="filteredItems" height="250">
				<el-table-column label="Add" width="70">
					<template #default="scope">
						<el-button size="small" @click="addItem(scope.row)"><el-icon><Plus /></el-icon></el-button>
					</template>
				</el-table-column>
				<el-table-column prop="title" label="Title"/>
				<el-table-column prop="category.title" label="Category"/>
			</el-table>
		</template>
	</el-dialog>
</template>
<style lang="scss">
.items-content {
	flex: 1;
	height: 100%;

	text-align: center;

	.item-wrapper {
		height: calc(100% - 36px);

		.piece-scrollbar {
			margin-top: 1em;
			width: 100%;

			.list-space {
				display: grid !important;
				grid-gap: 15px;
				grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
			}
		}
	}
}
.el-dialog {
	.dialog-search {
		margin-bottom: 10px;
	}
}
</style>