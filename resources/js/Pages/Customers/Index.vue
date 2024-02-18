<script>
import { router } from '@inertiajs/vue3';

export default {
	props: {
		customers: Array,
	},
	data() {
		return {
			search: '',
			select: 'name',
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
		openCreate() {
			router.get(route('customers.create'));
		},
	},
};
</script>

<template>
	<MainLayout title="Customers">
		<OverviewLayout title="Customer" :displayCards="!!filteredCustomers.length" @openCreate="openCreate">
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
</template>
<style lang="scss">
.customer-list-space {
	display: grid !important;
	grid-gap: 15px;
	grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
}
</style>