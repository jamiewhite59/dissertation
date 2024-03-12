<script>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';

export default {
	props: {
		category: Object,

	},
	data() {
		return {
			categoryForm: reactive({
				title: this.category ? this.category.title : '',
				description: this.category ? this.category.description : '',
			}),
			rules: reactive({
				title: [
					{ required: true, message: 'Title is required', trigger: 'blur', },
				],
			}),
		};
	},
	computed: {
		changes() {
			if (this.category) {
				return Object.keys(this.categoryForm).some((key) => this.category[key] !== this.categoryForm[key]);
			} else {
				return true;
			}
		},
	},
	methods: {
		save() {
			this.validate()
				.then((valid) => {
					if (valid) {
						if (this.category) {
							router.patch(route('categories.update', this.category.id), this.categoryForm);
						} else {
							router.post(route('categories.store', this.categoryForm));
						}
					}
				});
		},
		validate() {
			return this.$refs.formRef.validate()
				.then((valid) => {
					return true;
				})
				.catch((err) => {
					return false;
				});
		},
		resetForm() {
			this.categoryForm.title = '';
			this.categoryForm.description = '';
			this.$refs.formRef.resetFields();
		},
	},
};
</script>
<template>
	<el-form class="create-form" ref="formRef" label-position="top" :model="categoryForm" :rules="rules" @input="$emit('change', changes)">
		<el-form-item label="Title" prop="title" required>
			<el-input v-model="categoryForm.title" />
		</el-form-item>
		<el-form-item label="Description" prop="description">
			<el-input v-model="categoryForm.description" type="textarea" :autosize="{minRows: 3, maxRows: 6}" :rows="4" />
		</el-form-item>
	</el-form>
</template>
<style lang="scss">

</style>