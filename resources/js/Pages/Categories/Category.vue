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
		addItem(id) {
			router.put(route('categories.addItem', this.category.id), {id: id,});
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
					<el-container>
						<el-text size="large" tag="b" style="margin-bottom: 1em;">Items ({{ category.items.length }})</el-text>
					</el-container>
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
	<el-dialog v-model="itemDialogVisible" width="30%" style="min-height:400px;" align-center @opened="$refs.itemSearch.focus()">
		<template #header>Items</template>
		<template #default>
			<el-input class="dialog-search" v-model="itemSearch" ref="itemSearch" placeholder="Search Items" clearable />
			<el-scrollbar height="250px">
				<el-row v-for="item in filteredItems" :key="item.id" style="margin-bottom:10px">
					<el-col :span="3"><el-button size="small" @click="addItem(item.id)"><el-icon><Plus /></el-icon></el-button></el-col>
					<el-col :span="21">{{ item.title }}</el-col>
				</el-row>
			</el-scrollbar>
		</template>
	</el-dialog>
</template>
<style lang="scss">
.items-content {
	flex: 1;
	height: 100%;

	.item-wrapper {
		height: calc(100% - 36px);

		.piece-scrollbar {
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