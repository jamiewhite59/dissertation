<script>
export default {
	props: {
		events: Array,
		errors: Object,
		flash: Object,
	},
	data() {
		return {
			search: '',
			statusSelected: [],
			createDialogVisible: false,
		};
	},
	computed: {
		augmentedEvents() {
			let now = new Date().getTime();
			return this.events.map((event) => {
				let startDate = new Date(event.start_date).getTime();
				let endDate = new Date(event.end_date)?.getTime();
				if (now < startDate) {
					event.status = 'upcoming';
				} else if (now > startDate && (now < endDate || !endDate)) {
					event.status = 'active';
				} else {
					event.status = 'complete';
				}
				return event;
			});
		},
		filteredEvents() {
			if (this.statusSelected.length) {
				return this.augmentedEvents.filter((event) => {
					return this.statusSelected.some((status) => event.status === status.value);
				});
			} else {
				return this.events;
			}
		},
		searchedEvents() {
			if (this.search) {
				return this.filteredEvents.filter((event) => {
					return event.title.toLowerCase().includes(this.search.toLowerCase());
				});
			} else {
				return this.filteredEvents;
			}
		},
		statusOptions() {
			return [
				{
					value: 'active',
					label: 'Active',
					type: 'success',
				},
				{
					value: 'upcoming',
					label: 'Upcoming',
					type: 'info',
				},
				{
					value: 'complete',
					label: 'Complete',
					type: 'danger',
				},
			];
		},
	},
	methods: {
		createEvent() {
			this.$refs.eventForm.save()
				.then((res) => {
					this.createDialogVisible = false;
				})
				.catch((res) => {});
		},
		resetForm() {
			this.$refs.eventForm.resetForm();
		},
	},
};
</script>

<template>
	<MainLayout title="Events" :errors="errors" :flash="flash">
		<OverviewLayout title="Event" :displayCards="!!searchedEvents.length" @openCreate="createDialogVisible = true">
			<template #extra>
				<el-select v-model="statusSelected" multiple placeholder="Filter Events" style="width: 250px">
					<el-option v-for="option in statusOptions" :key="option.value" :label="option.label" :value="option">
						<el-tag :type="option.type" size="small" round>{{ option.label }}</el-tag>
					</el-option>
					<template #tag>
						<el-tag v-for="status in statusSelected" :key="status.value" :type="status.type">{{ status.label }}</el-tag>
					</template>
				</el-select>
			</template>
			<template #search>
				<el-input class="overview-search" v-model="search" placeholder="Search Events" clearable />
			</template>
			<template #cards>
				<el-container class="event-list-space">
					<EventItem v-for="event in searchedEvents" :key="event.id" :event="event"/>
				</el-container>
			</template>
		</OverviewLayout>
	</MainLayout>
	<el-dialog v-model="createDialogVisible" width="30%" style="min-height: 400px" align-center @opened="$refs.eventForm.$refs.eventTitle.focus()" @closed="resetForm">
		<template #header>Create Event</template>
		<template #default>
			<EventForm ref="eventForm" @keyup.enter="createEvent" />
		</template>
		<template #footer>
			<el-button type="primary" @click="createDialogVisible = false">Cancel</el-button>
			<el-button type="primary" @click="createEvent">Create</el-button>
		</template>
	</el-dialog>
</template>
<style lang="scss">
.event-list-space {
	display: grid !important;
	grid-gap: 15px;
	grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
}
</style>
