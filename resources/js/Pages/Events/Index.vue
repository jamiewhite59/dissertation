<script>
import { router } from '@inertiajs/vue3';

export default {
	props: {
		events: Array,
	},
	data() {
		return {
			search: '',
			statusSelected: [],
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
					console.debug('selected', this.statusSelected, event.status);
					return this.statusSelected.includes(event.status);
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
					value: 'upcoming',
					label: 'Upcoming',
				},
				{
					value: 'active',
					label: 'Active',
				},
				{
					value: 'complete',
					label: 'Complete',
				},
			];
		},
	},
	methods: {
		openCreate() {
			router.get(route('events.create'));
		},
	},
};
</script>

<template>
	<MainLayout title="Events">
		<OverviewLayout title="Event" :displayCards="!!searchedEvents.length" @openCreate="openCreate">
			<template #extra>
				<el-select v-model="statusSelected" multiple placeholder="Select" style="width: 300px">
					<el-option v-for="option in statusOptions" :key="option.value" :label="option.label" :value="option.value" />
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
</template>
<style lang="scss">
.event-list-space {
	display: grid !important;
	grid-gap: 15px;
	grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
}
</style>
