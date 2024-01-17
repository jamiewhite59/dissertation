<script>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Plus } from '@element-plus/icons-vue';

export default {
	components: {
		MainLayout,
		Plus,
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

	},
};
</script>

<template>
	<MainLayout title="Customers">
		<el-container class="customer-create">
			<el-header>
				<span>Search</span>
				<el-select v-model="search" filterable disabled placeholder="" style="width:250px">
					<el-option v-for="option in options" :key="option.value" :label="option.label" :value="option.value" />
				</el-select>
				<a :href="route('customers.create')">
					<el-button type="primary">
						Create
						<el-icon><Plus/></el-icon>
					</el-button>
				</a>
			</el-header>
			<el-main/>
		</el-container>
	</MainLayout>
</template>