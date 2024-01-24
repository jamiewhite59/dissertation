<script>
import MainLayout from '@/Layouts/MainLayout.vue';
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';

export default {
	components: {
		MainLayout,
	},
	props: {
		event: Object,
		errors: Object,
	},
	data() {
		return {
			eventForm: reactive({
				title: this.event ? this.event.title : '',
				start_date: this.event ? this.event.start_date: null,
				end_date: this.event ? this.event.end_date: null,
				icon: this.event ? this.event.icon : '',
			}),
			rules: reactive({
				title: [
					{ required: true, message: 'Title is required', trigger: 'blur', },
				],
				start_date: [
					{ required: true, message: 'Start Date is required', trigger: 'blur', },
				],
			}),
		};
	},
	watch: {
		errors() {
			let message = '';
			Object.values(this.errors).forEach((err) => {
				message = message.concat('<li>', err, '</li>');
			});
			ElMessage.error({
				dangerouslyUseHTMLString: true,
				message: '<strong>Error saving form</strong><ul>' + message + '</ul>',
				grouping: true,
			});
		},
	},
	methods: {
		create() {
			this.validate()
				.then((valid) => {
					if (valid) {
						router.post(route('events.store', this.eventForm));
					}
				})
			;
		},
		save() {
			this.validate()
				.then((valid) => {
					if (valid) {
						router.patch(route('events.update', this.event.id), this.eventForm);
					}
				});
		},
		remove() {
			ElMessageBox.confirm(
				'Are you sure you want to permanently delete this event?',
				'Delete Event',
				{
					confirmButtonText: 'Delete',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.delete(route('events.destroy', this.event.id));
			}).catch(() => {});
		},
		openIndex() {
			router.get(route('events.index'));
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
	},
};
</script>

<template>
	<MainLayout title="Events">
		<el-container direction="vertical">
			<el-container>
				<el-form ref="formRef" label-position="top" :model="eventForm" :rules="rules">
					<el-form-item label="Title" prop="title" required>
						<el-input v-model="eventForm.title"/>
					</el-form-item>
					<el-form-item label="Start Date" prop="start_date" required>
						<el-date-picker v-model="eventForm.start_date" type="date" clearable />
					</el-form-item>
					<el-form-item label="End Date" prop="end_date">
						<el-date-picker v-model="eventForm.end_date" type="date" clearable />
					</el-form-item>
					<el-form-item label="Icon" prop="icon">
						<el-input v-model="eventForm.icon"/>
					</el-form-item>
				</el-form>
			</el-container>
			<el-container direction="vertical">
				<el-row>
					<el-col :span="24" style="text-align: end;">
						<el-button @click="openIndex">Cancel</el-button>
					</el-col>
				</el-row>
				<el-row justify="space-evenly">
					<el-col :span="8">
						<el-button v-if="event" @click="remove">Delete</el-button>
					</el-col>
					<el-col :span="8"/>
					<el-col :span="8" style="text-align: end;">
						<el-button v-if="event" @click="save">Save</el-button>
						<el-button v-else @click="create">Create</el-button>
					</el-col>
				</el-row>
			</el-container>
		</el-container>
	</MainLayout>
</template>