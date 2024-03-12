<script>
import { router } from '@inertiajs/vue3';

import { ElMessageBox } from 'element-plus';

export default {
	props: {
		category: Object,
		errors: Object,
	},
	data() {
		return {
			formChanges: false,
		};
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
	},
};
</script>
<template>
	<MainLayout :title="category ? category.title : 'Category'" :errors="errors">
		<CreateLayout :existing="category" :changes="formChanges" @remove="remove" @openIndex="openIndex" @save="save">
			<template #form>
				<el-container direction="vertical">
					<el-text size="large" tag="b">Details</el-text>
					<CategoryForm ref="categoryForm" :category="category" @change="(e) => typeof(e) === 'boolean' ? formChanges=e : ''" />
				</el-container>
			</template>
			<template #default>
				<el-col v-if="category.items.length" class="items-content">
					<el-container>
						<el-text size="large" tag="b" style="margin-bottom: 1em;">Items ({{ category.items.length }})</el-text>
					</el-container>
					<el-container class="item-wrapper">
						<el-scrollbar class="piece-scrollbar" height="100%">
							<el-container class="list-space">
								<ItemItem v-for="item in category.items" :key="item.id" :item="item" />
							</el-container>
						</el-scrollbar>
					</el-container>
				</el-col>
				<template v-else>No items associated to this category</template>
			</template>
		</CreateLayout>
	</MainLayout>
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
</style>