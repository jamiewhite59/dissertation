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
			let now = new Date().toISOString().split('T')[0];
			now = new Date(now).getTime();
			let startDate = new Date(this.event.start_date).getTime();
			let endDate = new Date(this.event.end_date)?.getTime();

			if (now < startDate) {
				return 'info';
			} else if (now >= startDate && (now <= endDate || !endDate)) {
				return 'success';
			} else {
				return 'danger';
			}
		},
		tooltipValue() {
			switch (this.status) {
			case 'info':
				return 'Upcoming';
			case 'success':
				return 'Active';
			default:
				return 'Complete';
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
	<el-card shadow="hover" style="text-align:center;" @click="openEdit(event.id)">
		<el-descriptions :title="event.title" :column="1">
			<template #extra>
				<el-tooltip effect="light" :content="tooltipValue" placement="top" >
					<el-tag :type="status" size="small" round effect="dark" style="height:15px;width:15px;"/>
				</el-tooltip>
			</template>
			<el-descriptions-item>
				<el-text tag="b">Start</el-text>
				{{ startDate }}
			</el-descriptions-item>
			<el-descriptions-item>
				<el-text tag="b">End</el-text>
				{{ endDate }}
			</el-descriptions-item>
		</el-descriptions>
	</el-card>
</template>
<style lang="scss">
</style>