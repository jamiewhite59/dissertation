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
				<el-space class="list-space" wrap size="small" prefixCls="space-item">
					<el-card class="list-card" v-for="customer in customers" :key="customer.id">
						<el-space direction="vertical">
							<el-text tag="b">{{ customer.name }}</el-text>
							<el-text>{{ customer.email }}</el-text>
							<el-text>{{ customer.phone_number }}</el-text>
						</el-space>
					</el-card>
				</el-space>
			</el-main>
		</el-container>
	</MainLayout>
</template>