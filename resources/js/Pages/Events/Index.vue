<script>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Plus } from '@element-plus/icons-vue';
import { router } from '@inertiajs/vue3';

export default {
	components: {
		MainLayout,
		Plus,
	},
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
		openEdit(id) {
			router.get(route('events.edit', id));
		},
	},
};
</script>

<template>
	<MainLayout title="Events">
		<el-container>
			<el-header class="event-index-create">
				<span>Search</span>
				<el-input v-model="search" placeholder="Search Events" clearable style="width:450px"/>
				<el-button type="primary" @click="openCreate">
					<template #icon>
						<el-icon><Plus/></el-icon>
					</template>
					Create
				</el-button>
			</el-header>
			<el-main class="event-index-list">
				<el-empty v-if="!events.length" description="No events" />
				<el-container v-else class="list-space">
					<el-card class="list-card" v-for="event in filteredEvents" :key="event.id" shadow="hover" @click="openEdit(event.id)">
						<el-descriptions :title="event.title" :column="1">
							<el-descriptions-item>Some event information</el-descriptions-item>
						</el-descriptions>
					</el-card>
				</el-container>
			</el-main>
		</el-container>
	</MainLayout>
</template>
<style lang="scss">
.event-index-create {
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 1em;
}

.event-index-list {
	flex: initial !important;

	.list-space {
		display: grid !important;
		grid-gap: 15px;
		grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));

		.space-item__item {
			width: 24%;
		}

		.list-card {
			text-align: center;
			height: 120px;
		}

		.el-card.is-hover-shadow:hover{
			border: 1px solid var(--el-color-primary);
			cursor: pointer;
		}
	}

}
</style>
