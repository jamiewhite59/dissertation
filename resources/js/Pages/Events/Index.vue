<script>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';

export default {
	props: {
		events: Array,
	},
	data() {
		return {
			search: '',
			statusSelected: [],
			createDialogVisible: false,
			eventForm: reactive({
				title: '',
				start_date: null,
				end_date: null,
				icon:  '',
			}),
			eventFormRules: reactive({
				title: [
					{ required: true, message: 'Title is required', trigger: 'blur', },
				],
				start_date: [
					{ required: true, message: 'Start Date is required', trigger: 'blur', },
				],
				end_date: [
					{ validator: this.validateEndDate, trigger: 'blur', },
				],
			}),
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
			this.createDialogVisible = true;
		},
		validateEndDate(rule, value, callback) {
			let currentStart = new Date(this.eventForm.start_date).toISOString();
			let currentEnd = new Date(this.eventForm.end_date).toISOString();
			if (this.eventForm.end_date && currentStart > currentEnd) {
				callback(new Error('Start date must be before end date'));
			} else {
				callback();
			}
		},
		validate() {
			return this.$refs.formRef.validate()
				.then((valid) => {
					return true;
				})
				.catch((err) => {
					return false;
				});
		},
		createEvent() {
			this.validate()
				.then((valid) => {
					if (valid) {
						router.post(route('events.store', this.eventForm));
						this.createDialogVisible = false;
					}
				});
		},
		resetForm() {
			this.eventForm.title = '';
			this.eventForm.start_date = null;
			this.eventForm.end_date = null;
			this.eventForm.icon = '';
			this.$refs.formRef.resetFields();
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
	<el-dialog v-model="createDialogVisible" width="30%" style="min-height: 400px" align-center @closed="resetForm">
		<template #header>Create Event</template>
		<template #default>
			<el-form class="create-form" ref="formRef" label-position="top" :model="eventForm" :rules="eventFormRules">
				<el-form-item label="Title" prop="title" required>
					<el-input v-model="eventForm.title" />
				</el-form-item>
				<el-form-item label="Icon" prop="icon">
					<el-input v-model="eventForm.icon" />
				</el-form-item>
				<el-form-item label="Start Date" prop="start_date" required>
					<el-date-picker v-model="eventForm.start_date" type="date" clearable style="width:100%" />
				</el-form-item>
				<el-form-item label="End Date" prop="end_date">
					<el-date-picker v-model="eventForm.end_date" type="date" clearable style="width:100%" />
				</el-form-item>
			</el-form>
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
