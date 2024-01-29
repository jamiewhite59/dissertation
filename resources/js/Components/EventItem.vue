<script>
import { router } from '@inertiajs/vue3';

export default {
	props: {
		event: Object,
	},
	data() {
		return {
		};
	},
	computed: {
		startDate() {
			return new Date(this.event.start_date).toLocaleDateString();
		},
		endDate() {
			if (this.event.end_date) {
				return new Date(this.event.end_date).toLocaleDateString();
			} else {
				return 'Ongoing';
			}
		},
		status() {
			let now = new Date().getTime();
			let startDate = new Date(this.event.start_date).getTime();
			let endDate = new Date(this.event.end_date)?.getTime();

			if (now < startDate) {
				return 'info';
			} else if (now > startDate && (now < endDate || !endDate)) {
				return 'success';
			} else {
				return 'danger';
			}
		},
	},
	methods: {
		openEdit(id) {
			router.get(route('events.edit', id));
		},
	},
};
</script>
<template>
	<el-card class="event-item" shadow="hover" @click="openEdit(event.id)">
		<el-descriptions :title="event.title" :column="1">
			<template #extra>
				<el-tag :type="status" effect="dark"/>
			</template>
			<el-descriptions-item>
				<el-text tag="b">Start Date</el-text>
				{{ startDate }}
			</el-descriptions-item>
			<el-descriptions-item>
				<el-text tag="b">End Date</el-text>
				{{ endDate }}
			</el-descriptions-item>
		</el-descriptions>
	</el-card>
</template>
<style lang="scss">
.event-item {
	text-align: center;
	height: 120px;
}
</style>