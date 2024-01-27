<script>
import { router } from '@inertiajs/vue3';

export default {
	props: {
		customer: Object,
		remove: Boolean,
	},
	data() {
		return {
		};
	},
	methods: {
		openEdit(id) {
			router.get(route('customers.edit', id));
		},
	},
};
</script>
<template>
	<el-card class="customer-item" shadow="hover" @click="openEdit(customer.id)">
		<el-descriptions :title="customer.name" :column="1">
			<template v-if="remove" #extra>
				<el-button size="small" @click.stop="$emit('removeCustomer', customer.id)"><el-icon><Minus/></el-icon></el-button>
			</template>
			<el-descriptions-item v-if="customer.company">{{ customer.company }}</el-descriptions-item>
			<el-descriptions-item>
				<span>{{ customer.email }}</span> <el-divider direction="vertical"/> <span>{{ customer.phone_number }}</span>
			</el-descriptions-item>
		</el-descriptions>
	</el-card>
</template>
<style lang="scss">
.customer-item {
	text-align: center;
	height: 120px;
	min-width: 120px;

	white-space: nowrap;

	&.add-card {
		display: flex;
		justify-content: center;
		align-items: center;

		.el-card__body {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;

			gap: 1em;

			.el-descriptions {
				overflow: hidden;
				text-overflow: ellipsis;
				white-space: nowrap;
			}
		}
	}
}
</style>