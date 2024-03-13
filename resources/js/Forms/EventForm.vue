<script>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';

export default {
	props: {
		event: Object,
	},
	data() {
		return {
			eventForm: reactive({
				title: this.event ? this.event.title : '',
				start_date: this.event ? this.event.start_date : null,
				end_date: this.event ? this.event.end_date : null,
				icon: this.event ? this.event.icon : '',
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
		changes() {
			if (this.event) {
				return Object.keys(this.eventForm).some((key) => this.event[key] !== this.eventForm[key]);
			} else {
				return true;
			}
		},
	},
	methods: {
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
		resetForm() {
			this.eventForm.title = '';
			this.eventForm.start_date = null;
			this.eventForm.end_date = null;
			this.eventForm.icon = '';
			this.$refs.formRef.resetFields();
		},
		save() {
			return new Promise((resolve, reject) => {
				this.validate()
					.then((valid) => {
						if (valid) {
							if (this.event) {
								router.patch(route('events.update', this.event.id), this.eventForm);
								resolve();
							} else {
								router.post(route('events.store', this.eventForm));
								resolve();
							}
						}
					});
			});
		},
	},
};
</script>
<template>
	<el-form class="create-form" ref="formRef" label-position="top" :model="eventForm" :rules="eventFormRules" @input="$emit('change', changes)">
		<el-form-item label="Title" prop="title" required>
			<el-input v-model="eventForm.title" ref="eventTitle" />
		</el-form-item>
		<el-form-item label="Icon" prop="icon">
			<el-input v-model="eventForm.icon" />
		</el-form-item>
		<el-form-item label="Start Date" prop="start_date" required>
			<el-date-picker v-model="eventForm.start_date" type="date" clearable style="width:100%" @change="$emit('change', changes)" />
		</el-form-item>
		<el-form-item label="End Date" prop="end_date">
			<el-date-picker v-model="eventForm.end_date" type="date" clearable style="width:100%" @change="$emit('change', changes)" />
		</el-form-item>
	</el-form>
</template>
<style lang="scss">

</style>