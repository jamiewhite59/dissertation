<script>
import { router } from '@inertiajs/vue3';

export default {
	data() {
		return {
			search: '',
		};
	},
	methods: {
		openCreate() {
			router.get(route('items.create'));
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
			<template #default>Test content</template>
		</OverviewLayout>
	</MainLayout>
</template>
<style lang="scss">

</style>