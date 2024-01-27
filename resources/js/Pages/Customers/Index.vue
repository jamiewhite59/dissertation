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
		<el-container>
			<el-header class="customer-index-create">
				<el-container class="customer-index-create-left">
					<el-button type="primary" @click="openCreate">
						<template #icon>
							<el-icon><Plus/></el-icon>
						</template>
						Create
					</el-button>
				</el-container>
				<el-input v-model="search" placeholder="Search Customers" clearable style="width:450px"/>
			</el-header>
			<el-main class="customer-index-list">
				<el-empty v-if="!filteredCustomers?.length" description="No Customers" />
				<el-container v-else class="list-space">
					<CustomerItem v-for="customer in filteredCustomers" :key="customer.id" :customer="customer"/>
				</el-container>
			</el-main>
		</el-container>
	</MainLayout>
</template>
<style lang="scss">
.customer-index-create {
	display: flex;
	justify-content: flex-end;
	align-items: center;
	gap: 1em;
	height: auto !important;

	padding-bottom: 20px;

	.customer-index-create-left{
		height: auto !important;
	}
}

.customer-index-list {
	flex: initial !important;

	padding-top: 0;

	.list-space {
		display: grid !important;
		grid-gap: 15px;
		grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
	}
}
</style>