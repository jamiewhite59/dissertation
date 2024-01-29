<script>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';

export default {
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
			var eventCustomerIds = this.eventCustomers.map((customer) => customer.id);
			var availableCustomers = this.customers.filter((customer) => !eventCustomerIds.includes(customer.id));
			if (this.search) {
				return availableCustomers.filter((customer) =>
					customer.name.toLowerCase().includes(this.search.toLowerCase()));
			} else {
				return availableCustomers;
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
		save(){
			this.validate()
				.then((valid) => {
					if (valid) {
						if (this.event){
							router.patch(route('events.update', this.event.id), this.eventForm);
						} else {
							router.post(route('events.store', this.eventForm));
						}
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
			this.dialogVisible = false;
			router.put(route('events.addCustomer', this.event.id), {id: id,});
		},
		removeCustomer(id) {
			router.put(route('events.removeCustomer', this.event.id), {id: id,});
		},
	},
};
</script>

<template>
	<MainLayout title="Events">
		<CreateLayout :existing="event" @remove="remove" @openIndex="openIndex" @save="save">
			<template #form>
				<el-form class="create-form" ref="formRef" label-position="top" :model="eventForm" :rules="rules">
					<el-form-item label="Title" prop="title" required>
						<el-input v-model="eventForm.title"/>
					</el-form-item>
					<el-form-item label="Icon" prop="icon">
						<el-input v-model="eventForm.icon"/>
					</el-form-item>
					<el-form-item label="Start Date" prop="start_date" required >
						<el-date-picker v-model="eventForm.start_date" type="date" clearable style="width:100%" />
					</el-form-item>
					<el-form-item label="End Date" prop="end_date">
						<el-date-picker v-model="eventForm.end_date" type="date" clearable  style="width:100%"/>
					</el-form-item>
				</el-form>
			</template>
			<template #default>
				<el-col class="customer-index-list" :xs="24" :sm="24" :md="24" :lg="16" :xl="16" v-if="event" direction="vertical">
					<el-text size="large" tag="b">Customers</el-text>
					<el-container class="customer-item-wrapper">
						<el-scrollbar class="customer-scrollbar" height="100%">
							<el-container class="list-space">
								<el-card class="customer-item add-card" shadow="hover" @click="openModal">
									<el-text tag="b" size="large">Add Customer</el-text>
									<el-icon><Plus/></el-icon>
								</el-card>
								<CustomerItem v-for="customer in eventCustomers" :key="customer.id" :customer="customer" :remove="true" @removeCustomer="removeCustomer"/>
							</el-container>
						</el-scrollbar>
					</el-container>
				</el-col>
			</template>
		</CreateLayout>
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
.customer-index-list {
	flex: initial !important;
	width: 100%;

	margin-bottom: 1em;

	.customer-item-wrapper {
		height: 100%;
		overflow: hidden;

		.customer-scrollbar {
			margin-top: 1em;

			width: 100%;
		}

		.list-space {
			display: grid !important;
			grid-gap: 15px;
			grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));

			height: auto;

			margin-bottom: 1em;
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