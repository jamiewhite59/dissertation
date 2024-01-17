<script>
import MainLayout from '@/Layouts/MainLayout.vue';
import { reactive } from 'vue';

export default {
	components: {
		MainLayout,
	},
	data() {
		return {
			customerForm: reactive({
				name: '',
				email: '',
				phone_number: '',
				company: '',
			}),
		};
	},
	methods: {
		test() {
			console.debug('form data', this.customerForm);
		},
	},
};
</script>

<template>
	<MainLayout title="Customers">
		<el-container direction="vertical">
			<el-container>
				<el-form label-position="top" :model="customerForm">
					<el-form-item label="Name">
						<el-input v-model="customerForm.name"/>
					</el-form-item>
					<el-form-item label="Email">
						<el-input v-model="customerForm.email"/>
					</el-form-item>
					<el-form-item label="Phone Number">
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
						<a :href="route('customers.index')">
							<el-button>Cancel</el-button>
						</a>
					</el-col>
				</el-row>
				<el-row justify="space-evenly">
					<el-col :span="8">
						<el-button>Delete</el-button>
					</el-col>
					<el-col :span="8"/>
					<el-col :span="8" style="text-align: end;">
						<el-button @click="test()">Save</el-button>
					</el-col>
				</el-row>
			</el-container>
		</el-container>
	</MainLayout>
</template>