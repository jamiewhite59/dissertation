<script>
import MainLayout from '@/Layouts/MainLayout.vue';
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { ElMessage } from 'element-plus';

export default {
	components: {
		MainLayout,
	},
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
			let message = '';
			Object.values(this.errors).forEach((err) => {
				message = message.concat('<li>', err, '</li>');
			});
			ElMessage.error({
				dangerouslyUseHTMLString: true,
				message: '<strong>Error saving form</strong><ul>' + message + '</ul>',
				grouping: true,
			});
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
			router.delete(route('customers.destroy', this.customer.id));
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
				<el-form ref="formRef" label-position="top" :model="customerForm">
					<el-form-item label="Name" prop="name">
						<el-input v-model="customerForm.name"/>
					</el-form-item>
					<el-form-item label="Email" prop="email">
						<el-input v-model="customerForm.email"/>
					</el-form-item>
					<el-form-item label="Phone Number" prop="phone_number">
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
						<el-button @click="remove">Delete</el-button>
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