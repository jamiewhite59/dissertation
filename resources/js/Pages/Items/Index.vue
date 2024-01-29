<script>
import { router } from '@inertiajs/vue3';

export default {
	props: {
		items: Array,
	},
	data() {
		return {
			search: '',
		};
	},
	computed: {
		filteredItems() {
			if (this.search) {
				return this.items.filter((item) => {
					let title = item.title ? item.title.toLowerCase().includes(this.search.toLowerCase()) : false;
					let description = item.description ? item.description.toLowerCase().includes(this.search.toLowerCase()) : false;
					let stock_type = item.stock_type ? item.stock_type.toLowerCase().includes(this.search.toLowerCase()) : false;
					return title || description || stock_type;
				});
			} else {
				return this.items;
			}
		},
	},
	methods: {
		openCreate() {
			router.get(route('items.create'));
		},
		openEdit(item) {
			router.get(route('items.edit', item.id));
		},
	},
};
</script>
<template>
	<MainLayout title="Items">
		<OverviewLayout @openCreate="openCreate">
			<template #search>
				<el-input v-model="search" placeholder="Search Items" clearable style="width:450px"/>
			</template>
			<template #default>
				<el-row justify="center">
					<el-col :span="16">
						<el-table :data="filteredItems" @row-click="openEdit">
							<el-table-column prop="title" label="Title" sortable/>
							<el-table-column prop="stock_type" label="Stock Type" sortable/>
							<el-table-column prop="description" label="Description"/>
						</el-table>
					</el-col>
				</el-row>
			</template>
		</OverviewLayout>
	</MainLayout>
</template>
<style lang="scss">

</style>