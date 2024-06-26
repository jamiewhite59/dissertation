<script>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';

export default {
	props: {
		item: Object,
		categories: Array,
	},
	data() {
		return {
			itemForm: reactive({
				title: this.item ? this.item.title : '',
				description: this.item ? this.item.description : '',
				image: this.item ? this.item.image : null,
				stock_type: this.item ? this.item.stock_type : 'bulk',
				quantity: this.item ? this.item.pieces.length : 0,
				category_id: this.item ? this.item.category_id : '',
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
	computed: {
		changes() {
			if (this.item) {
				var clone = Object.assign({}, this.itemForm);
				delete clone.quantity;
				return Object.keys(clone).some((key) => this.item[key] !== clone[key])
				|| Number(this.itemForm.quantity) !== this.item.pieces.length;
			} else {
				return true;
			}
		},
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
			return new Promise((resolve, reject) => {
				this.validateItemForm()
					.then((valid) => {
						if (valid) {
							if (this.item){
								router.patch(route('items.update', this.item.id), this.itemForm);
								resolve();
							} else {
								router.post(route('items.store', this.itemForm));
								resolve();
							}
						} else {
							reject();
						}
					});
			});
		},
		resetForm() {
			this.itemForm.title = '';
			this.itemForm.description = '';
			this.itemForm.image = null;
			this.itemForm.stock_type = 'bulk';
			this.itemForm.quantity = 0;
			this.itemForm.category_id = '',
			this.$refs.itemFormRef.resetFields();
		},
	},
};
</script>
<template>
	<el-form class="create-form" ref="itemFormRef" label-position="top" :model="itemForm" :rules="itemRules" @input="$emit('change', changes)">
		<el-form-item label="Title" prop="title" required>
			<el-input v-model="itemForm.title" ref="itemTitle"/>
		</el-form-item>
		<el-form-item label="Description" prop="description">
			<el-input v-model="itemForm.description"/>
		</el-form-item>
		<el-form-item label="Stock Type" prop="stock_type" required>
			<el-select v-model="itemForm.stock_type" @change="$emit('change', changes)">
				<el-option label="Bulk" value="bulk"/>
				<el-option label="Hire" value="hire"/>
			</el-select>
		</el-form-item>
		<!-- <el-form-item label="Image" prop="image">
			<el-input v-model="itemForm.image"/>
		</el-form-item> -->
		<el-form-item label="Quantity">
			<el-input v-model="itemForm.quantity" type="number" min="0" :disabled="itemForm.stock_type === 'hire'" />
		</el-form-item>
		<el-form-item label="Category" prop="category_id">
			<el-select v-model="itemForm.category_id" clearable @change="$emit('change', changes)">
				<el-option label="No Category" :value="''"/>
				<el-option v-for="category in categories" :key="category.id" :label="category.title" :value="category.id" />
			</el-select>
		</el-form-item>
	</el-form>
</template>
<style lang="scss">

</style>