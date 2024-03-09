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
				<template v-if="category.items.length">
					<div v-for="item in category.items" :key="item.id">{{ item.title }}</div>
				</template>
				<template v-else>No items associated to this category</template>
			</template>
		</CreateLayout>
	</MainLayout>
</template>
<style lang="scss">

</style>