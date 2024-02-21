
<script>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';

export default {
	props: {
		customer: Object,
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
	methods: {
		save() {
			this.validate()
				.then((valid) => {
					if (valid) {
						if (this.customer) {
							router.patch(route('customers.update', this.customer.id), this.customerForm);
						} else {
							router.post(route('customers.store', this.customerForm));
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
			this.customerForm.name = '';
			this.customerForm.email = '';
			this.customerForm.phone_number = '';
			this.customerForm.company = '';
			this.$refs.formRef.resetFields();
		},
	},
};
</script>
<template>
	<el-form class="create-form" ref="formRef" label-position="top" :model="customerForm" :rules="rules">
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
</template>
<style lang="scss">

</style>