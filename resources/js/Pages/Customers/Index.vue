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
			options: [
				{
					value: 'Option1',
					label: 'Option1',
				},
				{
					value: 'Option2',
					label: 'Option2',
				},
				{
					value: 'Option3',
					label: 'Option3',
				},
			],
			search: '',
		};
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
				<el-select v-model="search" filterable disabled placeholder="" style="width:250px">
					<el-option v-for="option in options" :key="option.value" :label="option.label" :value="option.value" />
				</el-select>
				<el-button type="primary" @click="openCreate">
					Create
					<el-icon><Plus/></el-icon>
				</el-button>
			</el-header>
			<el-main class="customer-index-list">
				<el-container class="list-space">
					<el-card class="list-card" v-for="customer in customers" :key="customer.id" shadow="hover" @click="openEdit(customer.id)">
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