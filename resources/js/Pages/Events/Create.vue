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
		eventItems: Array,
		items: Array,
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
			customerDialogVisible: false,
			itemDialogVisible: false,
			customerSearch: '',
			itemSearch: '',
			activeTab: this.event ? 'items' : 'information',
			tableSelection: [],
			actionValue: 'Allocate',
			actionInput: '',
		};
	},
	computed: {
		filteredCustomers() {
			var eventCustomerIds = this.eventCustomers.map((customer) => customer.id);
			var availableCustomers = this.customers.filter((customer) => !eventCustomerIds.includes(customer.id));
			if (this.customerSearch) {
				return availableCustomers.filter((customer) =>
					customer.name.toLowerCase().includes(this.customerSearch.toLowerCase()));
			} else {
				return availableCustomers;
			}
		},
		filteredItems() {
			if (this.itemSearch) {
				return this.items.filter((item) =>
					item.title.toLowerCase().includes(this.itemSearch.toLowerCase()));
			} else {
				return this.items;
			}
		},
	},
	methods: {
		save() {
			this.validate()
				.then((valid) => {
					if (valid) {
						if (this.event) {
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
		openCustomerDialog() {
			this.customerDialogVisible = true;
		},
		openItemDialog() {
			this.itemDialogVisible = true;
		},
		addCustomer(id) {
			this.customerDialogVisible = false;
			router.put(route('events.addCustomer', this.event.id), { id: id, });
		},
		removeCustomer(id) {
			router.put(route('events.removeCustomer', this.event.id), { id: id, });
		},
		addItem(item_id) {
			let data = {
				event_id: this.event.id,
				item_id: item_id,
			};

			router.put(route('events.addItem', this.event.id), data);
		},
		handleTableSelectionChange(val) {
			this.tableSelection.value = val;
		},
		allocatePiece() {
			let data = {
				event_id: this.event.id,
				piece_code: this.actionInput,
			};
			router.put(route('events.addItemPiece', this.event.id), data);
			this.actionInput = '';
		},
		checkoutPiece() {
			console.debug('TODO: checkout piece');
		},
		checkinPiece() {
			console.debug('TODO: checkin piece');
		},
		completePiece() {
			console.debug('TODO: complete piece');
		},
		removePiece() {
			let someData = {
				eventItem_id: '9b55d784-4ebb-4796-b12b-70a21cf668dd',
			};

			router.put(route('events.removeItemPiece', this.event.id), someData);
		},
		checkCodeInput(event) {
			if (event.keyCode === 13) {
				this.allocatePiece();
			}
		},
		removeItems() {
			ElMessageBox.confirm(
				'Are you sure you want to remove these item(s)?',
				'Delete Item',
				{
					confirmButtonText: 'Delete',
					type: 'error',
					center: true,
				}
			).then(() => {
				let ids = this.tableSelection.value.map(eventItem => eventItem.id);
				router.put(route('events.destroyItem', this.event.id), { ids: ids, });
			}).catch(() => {});
		},
		itemAction() {
			switch (this.actionValue) {
			case 'Allocate':
				this.allocatePiece();
				break;
			case 'Check-Out':
				this.checkoutPiece();
				break;
			case 'Check-In':
				this.checkinPiece();
				break;
			case 'Complete':
				this.completePiece();
				break;
			default:
				console.warn('Probably shouldnt be here lol');
			}
		},
	},
};
</script>

<template>
	<MainLayout :title="event ? event.title : 'Events'" :errors="errors">
		<el-tabs v-model="activeTab">
			<el-tab-pane v-if="event" class="items-pane" label="Items" name="items">
				<el-container class="items-pane-container" direction="vertical">
					<el-row class="item-action-container" direction="horizontal">
						<div class="item-action-button-container">
							<el-button type="primary" @click="openItemDialog">Add</el-button>
							<el-button type="primary" @click="removeItems" :disabled="!tableSelection.value?.length">Remove {{ tableSelection.value?.length ? '(' + tableSelection.value.length + ')' : '' }}</el-button>
						</div>
						<div class="item-action-dropdown">
							<el-dropdown class="item-function-dropdown" split-button type="primary" trigger="click" @click="itemAction">
								{{ actionValue }}
								<template #dropdown>
									<el-dropdown-item @click="actionValue='Allocate'">Allocate</el-dropdown-item>
									<el-dropdown-item @click="actionValue='Check-Out'">Check-Out</el-dropdown-item>
									<el-dropdown-item @click="actionValue='Check-In'">Check-In</el-dropdown-item>
									<el-dropdown-item @click="actionValue='Complete'">Complete</el-dropdown-item>
								</template>
							</el-dropdown>
							<el-input class="item-action-input" v-model="actionInput" placeholder="Enter Item Code" @keypress="checkCodeInput" />
						</div>
					</el-row>
					<el-container class="items-table-container">
						<el-table :data="eventItems" height="100%" @selection-change="handleTableSelectionChange">
							<el-table-column type="selection" width="55" />
							<el-table-column prop="item_title" label="Title" />
							<el-table-column prop="item_stock_type" label="Stock Type" />
							<el-table-column prop="piece_code" label="Code" />
							<el-table-column prop="status" label="Status" />
						</el-table>
					</el-container>
				</el-container>
			</el-tab-pane>
			<el-tab-pane label="Information" name="information">
				<CreateLayout :existing="event" @remove="remove" @openIndex="openIndex" @save="save">
					<template #form>
						<el-container direction="vertical">
							<el-text class="form-title" size="large" tag="b">Details</el-text>
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
						</el-container>
					</template>
					<template #default>
						<el-col class="customer-index-list" :xs="24" :sm="24" :md="24" :lg="16" :xl="16" v-if="event" direction="vertical">
							<el-text size="large" tag="b">Customers</el-text>
							<el-container class="customer-item-wrapper">
								<el-scrollbar class="customer-scrollbar" height="100%">
									<el-container class="list-space">
										<el-card class="customer-item add-card" shadow="hover" @click="openCustomerDialog">
											<el-text tag="b" size="large">Add Customer</el-text><el-icon><Plus /></el-icon>
										</el-card>
										<CustomerItem v-for="customer in eventCustomers" :key="customer.id" :customer="customer" :remove="true" @removeCustomer="removeCustomer" />
									</el-container>
								</el-scrollbar>
							</el-container>
						</el-col>
					</template>
				</CreateLayout>
			</el-tab-pane>
		</el-tabs>

	</MainLayout>
	<el-dialog v-model="customerDialogVisible" width="30%" style="min-height:400px;" align-center>
		<template #header>Customers</template>
		<template #default>
			<el-input class="dialog-search" v-model="customerSearch" placeholder="Search Customers" clearable />
			<el-scrollbar height="250px">
				<el-row v-for="customer in filteredCustomers" :key="customer.id" style="margin-bottom:10px">
					<el-col :span="3"><el-button size="small" @click="addCustomer(customer.id)"><el-icon><Plus /></el-icon></el-button></el-col>
					<el-col :span="21">{{ customer.name }}</el-col>
				</el-row>
			</el-scrollbar>
		</template>
	</el-dialog>
	<el-dialog v-model="itemDialogVisible" width="30%" style="min-height:400px;" align-center>
		<template #header>Items</template>
		<template #default>
			<el-input class="dialog-search" v-model="itemSearch" placeholder="Search Items" clearable />
			<el-scrollbar height="250">
				<el-row v-for="item in filteredItems" :key="item.id" style="margin-bottom:10px;">
					<el-col :span="3"><el-button size="small" @click="addItem(item.id)"><el-icon><Plus /></el-icon></el-button></el-col>
					<el-col style="display:flex; align-items:center;" :span="21">{{ item.title }}</el-col>
				</el-row>
			</el-scrollbar>
		</template>
	</el-dialog>
</template>
<style lang="scss">
.el-tabs {
	display: flex;
	flex-direction: column;

	height: 100%;

	.el-tabs__content {
		flex: 1;

		#pane-information {
			height: 100%;

			.customer-index-list {
				flex: 1;

				text-align: center;

				.customer-item-wrapper {
					height: calc(100% - 20px);

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
		}

		#pane-items {
			height: 100%;

			.item-action-container {
				justify-content: space-between;
				margin-bottom: 15px;

				.item-action-button-container {
					display: flex;

					button {
						width: 130px;
					}
				}

				.item-action-dropdown {
					display: flex;
					flex-direction: row;
					justify-content: center;
					align-items: center;

					.el-dropdown {
						width: 100%;
					}
				}

				.item-action-input {
					justify-content: flex-end;

					.el-input__wrapper {
						max-width: 25%;
						min-width: 315px;
					}
				}
			}

			.items-pane-container {
				height: 100%;

				.items-table-container {
					height: 94%;
				}
			}

			.item-function-dropdown {
				button {
					border-color: var(--el-button-bg-color);
					background-color: var(--el-button-bg-color);
					color: var(--el-button-text-color);

					&:hover {
						color: var(--el-button-hover-text-color);
						border-color: var(--el-button-hover-border-color);
						background-color: var(--el-button-hover-bg-color);
						outline: none;
					}
				}
			}
		}
	}
}

.el-dialog {
	.dialog-search {
		margin-bottom: 10px;
	}
}
</style>