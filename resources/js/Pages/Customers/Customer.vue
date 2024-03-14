<script>
import { router } from '@inertiajs/vue3';

import { ElMessageBox } from 'element-plus';

export default {
	props: {
		customer: Object,
		events: Array,
		errors: Object,
		flash: Object,
	},
	data() {
		return {
			changes: false,
			eventDialogVisible: false,
			eventSearch: '',
		};
	},
	computed: {
		augmentedEvents() {
			return this.events.map((event) => {
				event.start_date_formatted = new Date(event.start_date).toLocaleDateString();
				if (! event.end_date) {
					event.end_date_formatted = 'Ongoing';
				} else {
					event.end_date_formatted = new Date(event.end_date).toLocaleDateString();
				}
				return event;
			});
		},
		filteredEvents() {
			var customerEventIds = this.customer.events.map((event) => event.id);
			var availableEvents = this.augmentedEvents.filter((event) => ! customerEventIds.includes(event.id));
			if (this.eventSearch) {
				return availableEvents.filter((event) => {
					let search = this.eventSearch.toLowerCase();
					return event.title.toLowerCase().includes(search)
					|| event.start_date_formatted.toLowerCase().includes(search)
					|| event.end_date_formatted.toLowerCase().includes(search);
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
	<MainLayout :title="customer ? customer.name : 'Customers'" :errors="errors" :flash="flash">
		<CreateLayout :existing="customer" :changes="changes" @remove="remove" @openIndex="openIndex" @save="save">
			<template #form>
				<el-container direction="vertical">
					<el-text size="large" tag="b">Details</el-text>
					<CustomerForm ref="customerForm" :customer="customer" @change="(e) => typeof(e) === 'boolean' ? changes=e : ''" />
				</el-container>
			</template>
			<template #default>
				<el-col class="event-index-list" :xs="24" :sm="24" :md="24" :lg="16" :xl="16" direction="vertical">
					<el-text size="large" tag="b">Events ({{ customer.events.length }})</el-text>
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
	<el-dialog v-model="eventDialogVisible" width="30%" style="min-height:400px;" align-center @opened="$refs.eventSearch.focus()" @closed="eventSearch = ''">
		<template #header>Events</template>
		<template #default>
			<el-input class="dialog-search" v-model="eventSearch" ref="eventSearch" placeholder="Search Events" clearable/>
			<el-table :data="filteredEvents" height="250" style="height: 100%;">
				<el-table-column label="Add" width="70">
					<template #default="scope">
						<el-button size="small" @click="addEvent(scope.row.id)"><el-icon><Plus /></el-icon></el-button>
					</template>
				</el-table-column>
				<el-table-column prop="title" label="Title"/>
				<el-table-column label="Start">
					<template #default="scope">
						<el-text>{{ scope.row.start_date_formatted }}</el-text>
					</template>
				</el-table-column>
				<el-table-column label="End">
					<template #default="scope">
						<el-text>{{ scope.row.end_date_formatted }}</el-text>
					</template>
				</el-table-column>
			</el-table>
		</template>
	</el-dialog>
</template>
<style lang="scss">
.event-index-list {
	flex: 1;

	text-align: center;

	.event-item-wrapper {
		height: calc(100% - 36px);

		.event-scrollbar {
			margin-top: 1em;

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