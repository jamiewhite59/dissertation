<script>
import { router } from '@inertiajs/vue3';

import { ElMessageBox } from 'element-plus';

export default {
	props: {
		customer: Object,
		events: Array,
		errors: Object,
	},
	data() {
		return {
			changes: false,
			eventDialogVisible: false,
			eventSearch: '',
		};
	},
	computed: {
		filteredEvents() {
			var customerEventIds = this.customer.events.map((event) => event.id);
			var availableEvents = this.events.filter((event) => ! customerEventIds.includes(event.id));
			if (this.eventSearch) {
				console.debug('availavle', availableEvents);
				return availableEvents.filter((event) => {
					return event.title.toLowerCase().includes(this.eventSearch.toLowerCase());
				});
			} else {
				return availableEvents;
			}
		},
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
		addEvent(id) {
			router.put(route('customers.addEvent', this.customer.id), {id: id,});
		},
	},
};
</script>

<template>
	<MainLayout :title="customer ? customer.name : 'Customers'" :errors="errors">
		<CreateLayout :existing="customer" :changes="changes" @remove="remove" @openIndex="openIndex" @save="save">
			<template #form>
				<CustomerForm ref="customerForm" :customer="customer" @change="(e) => typeof(e) === 'boolean' ? changes=e : ''" />
			</template>
			<template #default>
				<el-col class="event-index-list" :xs="24" :sm="24" :md="24" :lg="16" :xl="16" direction="vertical">
					<el-container style="margin-bottom:1em;">
						<el-text size="large" tag="b">Events ({{ customer.events.length }})</el-text>
					</el-container>
					<el-container class="event-item-wrapper">
						<el-scrollbar class="event-scrollbar" height="100%">
							<el-container class="list-space">
								<AddCard addItem="Event" @addClicked="eventDialogVisible = true"/>
								<EventItem v-for="event in customer?.events" :key="event.id" :event="event" />
							</el-container>
						</el-scrollbar>
					</el-container>
				</el-col>
			</template>
		</CreateLayout>
	</MainLayout>
	<el-dialog v-model="eventDialogVisible" width="30%" style="min-height:400px;" align-center>
		<template #header>Events</template>
		<template #default>
			<el-input class="dialog-search" v-model="eventSearch" placeholder="Search Events" clearable />
			<el-scrollbar height="250px">
				<el-row v-for="event in filteredEvents" :key="event.id" style="margin-bottom:10px">
					<el-col :span="3"><el-button size="small" @click="addEvent(event.id)"><el-icon><Plus /></el-icon></el-button></el-col>
					<el-col :span="21">{{ event.title }}</el-col>
				</el-row>
			</el-scrollbar>
		</template>
	</el-dialog>
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

.el-dialog {
	.dialog-search {
		margin-bottom: 10px;
	}
}
</style>