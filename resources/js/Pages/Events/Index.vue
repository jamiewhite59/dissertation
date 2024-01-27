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
		<el-container>
			<el-header class="event-index-create">
				<el-container>
					<el-button type="primary" @click="openCreate">
						<template #icon>
							<el-icon><Plus/></el-icon>
						</template>
						Create
					</el-button>
				</el-container>
				<el-input v-model="search" placeholder="Search Events" clearable style="width:450px"/>
			</el-header>
			<el-main class="event-index-list">
				<el-empty v-if="!events.length" description="No Events" />
				<el-container v-else class="list-space">
					<EventItem v-for="event in filteredEvents" :key="event.id" :event="event"/>
				</el-container>
			</el-main>
		</el-container>
	</MainLayout>
</template>
<style lang="scss">
.event-index-create {
	display: flex;
	justify-content: flex-end;
	align-items: center;
	gap: 1em;
	height: auto;
}

.event-index-list {
	flex: initial !important;

	.list-space {
		display: grid !important;
		grid-gap: 15px;
		grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
	}

}
</style>
