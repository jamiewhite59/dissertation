<script>
import { router } from '@inertiajs/vue3';

import { ElMessageBox } from 'element-plus';

export default {
	props: {
		customer: Object,
		errors: Object,
	},
	data() {
		return {
			changes: false,
		};
	},
	methods: {
		remove() {
			ElMessageBox.confirm(
				'Are you sure you want to permanently delete this user?',
				'Delete User',
				{
					confirmButtonText: 'Delete',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.delete(route('customers.destroy', this.customer.id));
			}).catch(() => {});
		},
		openIndex() {
			router.get(route('customers.index'));
		},
		save() {
			this.$refs.customerForm.save();
		},
	},
};
</script>

<template>
	<MainLayout title="Customers" :errors="errors">
		<CreateLayout :existing="customer" :changes="changes" @remove="remove" @openIndex="openIndex" @save="save">
			<template #form>
				<CustomerForm ref="customerForm" :customer="customer" @change="(e) => typeof(e) === 'boolean' ? changes=e : ''" />
			</template>
			<template #default>
				<el-col class="event-index-list" :xs="24" :sm="24" :md="24" :lg="16" :xl="16" direction="vertical">
					<el-container style="margin-bottom:1em;">
						<el-text size="large" tag="b">Events</el-text>
					</el-container>
					<el-container class="event-item-wrapper">
						<el-scrollbar class="event-scrollbar" height="100%">
							<el-container class="list-space">
								<EventItem v-for="event in customer?.events" :key="event.id" :event="event" />
							</el-container>
						</el-scrollbar>
					</el-container>
				</el-col>
			</template>
		</CreateLayout>
	</MainLayout>
</template>
<style lang="scss">
.event-index-list {
	flex: 1;

	.event-item-wrapper {
		height: calc(100% - 36px);

		.event-scrollbar {
			width: 100%;

			.list-space {
				display: grid !important;
				grid-gap: 15px;
				grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
			}
		}
	}
}
</style>