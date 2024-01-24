<script>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Plus } from '@element-plus/icons-vue';
import { router } from '@inertiajs/vue3';

export default {
	components: {
		MainLayout,
		Plus,
	},
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
		displayCustomers() {
			if (this.search) {
				return this.customers.filter((customer) => {
					return customer[this.select] ? customer[this.select].toLowerCase().includes(this.search.toLowerCase()) : false;
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
		openEdit(id) {
			router.get(route('customers.edit', id));
		},
	},
};
</script>

<template>
	<MainLayout title="Customers">
		<el-container>
			<el-header class="customer-index-create">
				<span>Search</span>
				<el-input v-model="search" placeholder="Search Customers" clearable style="width:450px">
					<template #prepend>
						<el-select v-model="select" placholder="Filter By" style="width:150px">
							<el-option label="Name" value="name"/>
							<el-option label="Email" value="email"/>
							<el-option label="Company" value="company"/>
						</el-select>
					</template>
				</el-input>
				<el-button type="primary" @click="openCreate">
					<template #icon>
						<el-icon><Plus/></el-icon>
					</template>
					Create
				</el-button>
			</el-header>
			<el-main class="customer-index-list">
				<el-empty v-if="!customers.length" description="No customers" />
				<el-container v-else class="list-space">
					<el-card class="list-card" v-for="customer in displayCustomers" :key="customer.id" shadow="hover" @click="openEdit(customer.id)">
						<el-descriptions :title="customer.name" :column="1">
							<el-descriptions-item>{{ customer.email }}</el-descriptions-item>
							<el-descriptions-item>{{ customer.phone_number }}</el-descriptions-item>
						</el-descriptions>
					</el-card>
				</el-container>
			</el-main>
		</el-container>
	</MainLayout>
</template>