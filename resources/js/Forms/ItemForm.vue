<script>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';

export default {
	props: {
		item: Object,
	},
	data() {
		return {
			itemForm: reactive({
				title: this.item ? this.item.title : '',
				description: this.item ? this.item.description : '',
				image: this.item ? this.item.image : null,
				stock_type: this.item ? this.item.stock_type : 'bulk',
			}),
			itemRules: reactive({
				title: [
					{ required: true, message: 'Title is required', trigger: 'blur', },
				],
				stock_type: [
					{ required: true, message: 'Stock Type is required', trigger: 'blur', },
				],
				code: [],
			}),
		};
	},
	methods: {
		validateItemForm() {
			return this.$refs.itemFormRef.validate()
				.then((valid) => {
					return true;
				})
				.catch((err) => {
					return false;
				});
		},
		save() {
			this.validateItemForm()
				.then((valid) => {
					if (valid) {
						if (this.item){
							router.patch(route('items.update', this.item.id), this.itemForm);
						} else {
							router.post(route('items.store', this.itemForm));
						}
					}
				});
		},
		resetForm() {
			this.itemForm.title = '';
			this.itemForm.description = '';
			this.itemForm.image = null;
			this.itemForm.stock_type = 'bulk';
			this.$refs.itemFormRef.resetFields();
		},
	},
};
</script>
<template>
	<el-form class="create-form" ref="itemFormRef" label-position="top" :model="itemForm" :rules="itemRules">
		<el-form-item label="Title" prop="title" required>
			<el-input v-model="itemForm.title"/>
		</el-form-item>
		<el-form-item label="Description" prop="description">
			<el-input v-model="itemForm.description"/>
		</el-form-item>
		<el-form-item label="Stock Type" prop="stock_type" required>
			<el-select v-model="itemForm.stock_type">
				<el-option label="Bulk" value="bulk"/>
				<el-option label="Hire" value="hire"/>
			</el-select>
		</el-form-item>
		<el-form-item label="Image" prop="image">
			<el-input v-model="itemForm.image"/>
		</el-form-item>
	</el-form>
</template>
<style lang="scss">

</style>