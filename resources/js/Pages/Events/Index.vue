<script>
import { router } from '@inertiajs/vue3';

export default {
	props: {
		events: Array,
	},
	data() {
		return {
			search: '',
		};
	},
	computed: {
		filteredEvents() {
			if (this.search) {
				return this.events.filter((event) => {
					return event.title.toLowerCase().includes(this.search.toLowerCase());
				});
			} else {
				return this.events;
			}
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
		<OverviewLayout title="Event" :displayCards="!!filteredEvents.length" @openCreate="openCreate">
			<template #search>
				<el-input v-model="search" placeholder="Search Events" clearable style="width:450px"/>
			</template>
			<template #cards>
				<el-container class="event-list-space">
					<EventItem v-for="event in filteredEvents" :key="event.id" :event="event"/>
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
