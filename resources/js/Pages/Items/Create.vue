<script>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';


export default {
	props: {
		item: Object,
		errors: Object,
	},
	data() {
		return {
			itemForm: reactive({
				title: this.item ? this.item.title : '',
				description: this.item ? this.item.description : '',
				image: this.item ? this.item.image : null,
				stock_type: this.item ? this.item.stock_type : 'bulk',
			}),
			rules: reactive({
				title: [
					{ required: true, message: 'Title is required', trigger: 'blur', },
				],
				stock_type: [
					{ required: true, message: 'Stock Type is required', trigger: 'blur', },
				],
			}),
		};
	},
	watch: {
		errors() {
			if (Object.keys(this.errors).length) {
				let message = '';
				Object.values(this.errors).forEach((err) => {
					message = message.concat('<li>', err, '</li>');
				});
				ElMessage.error({
					dangerouslyUseHTMLString: true,
					message: '<strong>Error saving form</strong><ul>' + message + '</ul>',
					grouping: true,
				});
			}
		},
	},
	methods: {
		remove() {
			ElMessageBox.confirm(
				'Are you sure you want to permanently delete this item?',
				'Delete Item',
				{
					confirmButtonText: 'Delete',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.delete(route('items.destroy', this.item.id));
			}).catch(() => {});
		},
		openIndex() {
			router.get(route('items.index'));
		},
		save() {
			this.validate()
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
		validate() {
			return this.$refs.formRef.validate()
				.then((valid) => {
					return true;
				})
				.catch((err) => {
					return false;
				});
		},
	},
};
</script>
<template>
	<MainLayout title="Items">
		<CreateLayout :existing="item" @remove="remove" @openIndex="openIndex" @save="save">
			<template #form>
				<el-form class="create-form" ref="formRef" label-position="top" :model="itemForm" :rules="rules">
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
			<template #default>
				<el-col class="item-content" :xs="24" :sm="24" :md="24" :lg="16" :xl="16" direction="vertical">
					<el-text>Pieces for this item?</el-text>
				</el-col>
			</template>
		</CreateLayout>
	</MainLayout>
</template>
<style lang="scss">
.item-content {
	flex: initial !important;
	width: 100%;

	margin-bottom: 1em;
}
</style>