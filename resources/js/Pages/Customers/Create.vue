<script>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';

export default {
	props: {
		customer: Object,
		errors: Object,
	},
	data() {
		return {
			customerForm: reactive({
				name: this.customer ? this.customer.name : '',
				email: this.customer ? this.customer.email : '',
				phone_number: this.customer ? this.customer.phone_number : '',
				company: this.customer ? this.customer.company : '',
			}),
			rules: reactive({
				name: [
					{ required: true, message: 'Name is required', trigger: 'blur', },
				],
				email: [
					{required: true, message: 'Email is required', trigger: 'blur', },
					{type: 'email', message: 'Valid email is required',},
				],
				phone_number: [
					{ required: true, message: 'Phone Number is required', trigger: 'blur', },
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
		create() {
			this.validate()
				.then((valid) => {
					if (valid) {
						router.post(route('customers.store', this.customerForm));
					}
				})
			;
		},
		save() {
			this.validate()
				.then((valid) => {
					if (valid) {
						router.patch(route('customers.update', this.customer.id), this.customerForm);
					}
				});
		},
		remove() {
			ElMessageBox.confirm(
				'Are you sure you want to permanently delete this user?',
				'Delete User',
				{
					confirmButtonText: 'Delete',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.delete(route('customers.destroy', this.customer.id));
			}).catch(() => {});
		},
		openIndex() {
			router.get(route('customers.index'));
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
	<MainLayout title="Customers">
		<el-container direction="vertical">
			<el-container>
				<el-form ref="formRef" label-position="top" :model="customerForm" :rules="rules">
					<el-form-item label="Name" prop="name" required>
						<el-input v-model="customerForm.name"/>
					</el-form-item>
					<el-form-item label="Email" prop="email" required>
						<el-input v-model="customerForm.email"/>
					</el-form-item>
					<el-form-item label="Phone Number" prop="phone_number" required>
						<el-input v-model="customerForm.phone_number"/>
					</el-form-item>
					<el-form-item label="Company" prop="company">
						<el-input v-model="customerForm.company"/>
					</el-form-item>
				</el-form>
			</el-container>
			<el-container direction="vertical">
				<el-row>
					<el-col :span="24" style="text-align: end;">
						<el-button @click="openIndex">Cancel</el-button>
					</el-col>
				</el-row>
				<el-row justify="space-evenly">
					<el-col :span="8">
						<el-button v-if="customer" @click="remove">Delete</el-button>
					</el-col>
					<el-col :span="8"/>
					<el-col :span="8" style="text-align: end;">
						<el-button v-if="customer" @click="save">Save</el-button>
						<el-button v-else @click="create">Create</el-button>
					</el-col>
				</el-row>
			</el-container>
		</el-container>
	</MainLayout>
</template>