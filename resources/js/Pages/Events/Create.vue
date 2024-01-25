<script>
import MainLayout from '@/Layouts/MainLayout.vue';
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';
import { Plus } from '@element-plus/icons-vue';

export default {
	components: {
		MainLayout,
		Plus,
	},
	props: {
		event: Object,
		customers: Array,
		errors: Object,
		eventCustomers: Array,
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
				end_date: [
					{ validator: this.validateEndDate, trigger: 'blur', },
				],
			}),
			dialogVisible: false,
			search: '',
		};
	},
	computed: {
		filteredCustomers() {
			if (this.search) {
				return this.customers.filter((customer) => customer.name.toLowerCase().includes(this.search.toLowerCase()));
			} else {
				return this.customers;
			}
		},
	},
	watch: {
		errors() {
			if (Object.keys(this.errors).length) {
				let message = '';
				Object.values(this.errors).forEach((err) => {
					message = message.concat('<li>', err, '</li>');
				});
				ElMessage.error({
					dangerouslyUseHTMLString: true,
					message: '<strong>Error saving form</strong><ul>' + message + '</ul>',
					grouping: true,
				});
			}
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
		validateEndDate(rule, value, callback) {
			let currentStart = new Date(this.eventForm.start_date).toISOString();
			let currentEnd = new Date(this.eventForm.end_date).toISOString();
			if (this.eventForm.end_date && currentStart > currentEnd) {
				callback(new Error('Start date must be before end date'));
			} else {
				callback();
			}
		},
		openCustomer(id) {
			router.get((route('customers.edit', id)));
		},
		openModal() {
			this.dialogVisible = true;
		},
		addCustomer(id) {
			console.debug('add customer', id);
			this.dialogVisible = false;
			router.put(route('events.addCustomer', this.event.id), {id: id,});
		},
	},
};
</script>

<template>
	<MainLayout title="Events">
		<el-container direction="vertical">
			<el-container>
				<el-form class="event-info-form" ref="formRef" label-position="top" :model="eventForm" :rules="rules">
					<el-row :gutter="30">
						<el-col :span="12">
							<el-form-item label="Title" prop="title" required>
								<el-input v-model="eventForm.title"/>
							</el-form-item>
						</el-col>
						<el-col :span="12">
							<el-form-item label="Icon" prop="icon">
								<el-input v-model="eventForm.icon"/>
							</el-form-item>
						</el-col>
					</el-row>
					<el-row :gutter="30">
						<el-col :span="12">
							<el-form-item label="Start Date" prop="start_date" required >
								<el-date-picker v-model="eventForm.start_date" type="date" clearable style="width:100%" />
							</el-form-item>
						</el-col>
						<el-col :span="12">
							<el-form-item label="End Date" prop="end_date">
								<el-date-picker v-model="eventForm.end_date" type="date" clearable  style="width:100%"/>
							</el-form-item>
						</el-col>
					</el-row>
				</el-form>
			</el-container>
			<el-container v-if="event" direction="vertical" class="customer-index-list">
				<el-text size="large" tag="b">Customers</el-text>
				<el-container class="list-space">
					<el-card class="list-card" v-for="customer in eventCustomers" :key="customer.id" shadow="hover" @click="openCustomer(customer.id)">
						<el-descriptions :title="customer.name" :column="1">
							<el-descriptions-item>{{ customer.email }}</el-descriptions-item>
							<el-descriptions-item>{{ customer.phone_number }}</el-descriptions-item>
						</el-descriptions>
					</el-card>
					<el-card class="list-card add-card" shadow="hover" @click="openModal">
						<el-text tag="b" size="large">Add Customer</el-text>
						<el-icon><Plus/></el-icon>
					</el-card>
				</el-container>
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
	<el-dialog v-model="dialogVisible" width="30%" style="height:400px" align-center>
		<template #header>Customers</template>
		<template #default>
			<el-input class="customer-search" v-model="search" placeholder="Search Customers" clearable/>
			<el-scrollbar max-height="250px">
				<el-row class="customer-row" v-for="customer in filteredCustomers" :key="customer.id" style="marginBottom:10px">
					<el-col :span="3"><el-button size="small" @click="addCustomer(customer.id)"><el-icon><Plus/></el-icon></el-button></el-col>
					<el-col class="customer-name" :span="21"><el-text>{{ customer.name }}</el-text></el-col>
				</el-row>
			</el-scrollbar>
		</template>
	</el-dialog>
</template>
<style lang="scss">
.event-info-form {
	width: 100%;
}

.customer-index-list {
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

			&.add-card {
				display: flex;
				justify-content: center;
				align-items: center;

				.el-card__body {
					display: flex;
					flex-direction: column;
					justify-content: center;
					align-items: center;

					gap: 1em;
				}
			}
		}

		.el-card.is-hover-shadow:hover{
			border: 1px solid var(--el-color-primary);
			cursor: pointer;
		}
	}
}

.el-dialog {
	.customer-search {
		margin-bottom: 20px;
	}

	.customer-row {
		.customer-name {
			display: flex;
			align-items: center;
		}
	}
}
</style>