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
			<el-descriptions-item>Some event information</el-descriptions-item>
		</el-descriptions>
	</el-card>
</template>
<style lang="scss">
.event-item {
	text-align: center;
	height: 120px;
}
</style>