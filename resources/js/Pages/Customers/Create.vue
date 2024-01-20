<script>
import MainLayout from '@/Layouts/MainLayout.vue';
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';

export default {
	components: {
		MainLayout,
	},
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
		};
	},
	methods: {
		save() {
			console.debug('form data', this.customerForm);
			if (this.customer) {
				console.debug('saving customer data');
			} else {
				console.debug('creating new customer');
				// router.post(route('customers.create', this.customerForm));
			}
		},
		openIndex() {
			router.get(route('customers.index'));
		},
	},
};
</script>

<template>
	<MainLayout title="Customers">
		<el-container direction="vertical">
			<el-container>
				<el-form label-position="top" :model="customerForm">
					<el-form-item label="Name" required>
						<el-input v-model="customerForm.name"/>
					</el-form-item>
					<el-form-item label="Email" required>
						<el-input v-model="customerForm.email"/>
					</el-form-item>
					<el-form-item label="Phone Number" required>
						<el-input v-model="customerForm.phone_number"/>
					</el-form-item>
					<el-form-item label="Company">
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
						<el-button>Delete</el-button>
					</el-col>
					<el-col :span="8"/>
					<el-col :span="8" style="text-align: end;">
						<el-button @click="save()">Save</el-button>
					</el-col>
				</el-row>
			</el-container>
		</el-container>
	</MainLayout>
</template>