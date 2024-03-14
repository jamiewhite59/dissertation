<script>
export default {
	props: {
		customers: Array,
		errors: Object,
		flash: Object,
	},
	data() {
		return {
			search: '',
			select: 'name',
			createDialogVisible: false,
		};
	},
	computed: {
		filteredCustomers() {
			if (this.search) {
				return this.customers.filter((customer) => {
					let name = customer.name ? customer.name.toLowerCase().includes(this.search.toLowerCase()) : false;
					let email = customer.email ? customer.email.toLowerCase().includes(this.search.toLowerCase()) : false;
					let company = customer.company ? customer.company.toLowerCase().includes(this.search.toLowerCase()) : false;
					let phone = customer.phone_number ? customer.phone_number.toLowerCase().includes(this.search.toLowerCase()) : false;
					return  name || email || company || phone;
				});
			} else {
				return this.customers;
			}
		},
	},
	methods: {
		createCustomer() {
			this.$refs.customerForm.save()
				.then((res) => {
					this.createDialogVisible = false;
				})
				.catch((err) => {});
		},
		resetForm() {
			this.$refs.customerForm.resetForm();
		},
	},
};
</script>

<template>
	<MainLayout title="Customers" :errors="errors" :flash="flash">
		<OverviewLayout title="Customer" :displayCards="!!filteredCustomers.length" @openCreate="createDialogVisible = true">
			<template #search>
				<el-input class="overview-search" v-model="search" placeholder="Search Customers" clearable />
			</template>
			<template #cards>
				<el-container class="customer-list-space">
					<CustomerItem v-for="customer in filteredCustomers" :key="customer.id" :customer="customer"/>
				</el-container>
			</template>
		</OverviewLayout>
	</MainLayout>
	<el-dialog v-model="createDialogVisible" width="30%" style="min-height: 400px" align-center @opened="$refs.customerForm.$refs.customerName.focus()" @closed="resetForm">
		<template #header>Create Customer</template>
		<template #default>
			<CustomerForm ref="customerForm" @keyup.enter="createCustomer" />
		</template>
		<template #footer>
			<el-button type="primary" @click="createDialogVisible = false">Cancel</el-button>
			<el-button type="primary" @click="createCustomer">Create</el-button>
		</template>
	</el-dialog>
</template>
<style lang="scss">
.customer-list-space {
	display: grid !important;
	grid-gap: 15px;
	grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
}
</style>