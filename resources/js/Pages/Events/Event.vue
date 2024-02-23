<script>
import { router } from '@inertiajs/vue3';
import { ElMessageBox } from 'element-plus';

export default {
	props: {
		event: Object,
		customers: Array,
		errors: Object,
		items: Array,
	},
	data() {
		return {
			customerDialogVisible: false,
			itemDialogVisible: false,
			customerSearch: '',
			itemSearch: '',
			activeTab: this.event ? 'items' : 'information',
			tableSelection: [],
			actionValue: 'Allocate',
			actionInput: '',
			formChanges: false,
		};
	},
	computed: {
		filteredCustomers() {
			var eventCustomerIds = this.event.customers.map((customer) => customer.id);
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
		augmentedItems() {
			let items = this.event.items;
			let newItems = [];
			items.forEach((item) => {
				if (! (newItems.find((newItem) => newItem.item_id === item.item_id && item.item_stock_type === 'bulk'))) {
					newItems.push(item);
				}
			});
			return newItems;
		},
		itemQuantities() {
			let counted = {};
			this.event.items.forEach((item) => {
				if (counted[item.item_id]?.quantity) {
					counted[item.item_id].quantity++;
				} else {
					counted[item.item_id] = {
						quantity: 1,
						stock_type: item.item_stock_type,
					};
				}
			});
			return counted;
		},
		bulkAllocationAllowed() {
			if (this.tableSelection.value?.length !== 1) {
				return false;
			}
			if (this.tableSelection.value[0].item_stock_type !== 'bulk') {
				return false;
			}
			return true;
		},
	},
	methods: {
		save() {
			this.$refs.eventForm.save();
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
		openCustomer(id) {
			router.get((route('customers.edit', id)));
		},
		addCustomer(id) {
			this.customerDialogVisible = false;
			router.put(route('events.addCustomer', this.event.id), { id: id, });
		},
		removeCustomer(id) {
			ElMessageBox.confirm(
				'Are you sure you want to remove this customer from this event?',
				'Remove Customer',
				{
					confirmButtonText: 'Remove',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.put(route('events.removeCustomer', this.event.id), { id: id, });
			}).catch(() => {});
		},
		addItem(item_id) {
			let data = {
				event_id: this.event.id,
				item_id: item_id,
			};

			router.put(route('events.addItem', this.event.id), data);
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
		rowAllocate(item) {
			if (item.item_stock_type === 'hire') {
				ElMessageBox.prompt('Input piece code', 'Code', {
					confirmButtonText: 'Allocate',
					cancelButtonText: 'Cancel',
				}).then((value) => {
					this.allocatePieceCode(item, value.value);
				});
			} else {
				ElMessageBox.prompt('Input quantity', 'Quantity', {
					confirmButtonText: 'Allocate',
					cancelButtonText: 'Cancel',
				}).then((value) => {
					this.allocatePieceBulk(item, value.value);
				});
			}
		},
		allocatePieceCode(item, code) {
			let data = {
				event_id: this.event.id,
				piece_code: code ? code : this.actionInput,
				event_item_id: item?.id,
			};
			router.put(route('events.addItemPiece', this.event.id), data);
			this.actionInput = '';
		},
		allocatePieceBulk(item, quantity) {
			if (item.item_stock_type === 'bulk') {
				let data = {
					event_id: item.event_id,
					item_id: item.item_id,
					quantity: quantity,
				};
				router.put(route('events.allocateBulk', item.event_id), data);
			}
		},
		checkoutPiece() {
			let data = {
				event_id: this.event.id,
				piece_code: this.actionInput,
			};
			router.put(route('events.checkoutPiece', this.event.id), data);
		},
		checkinPiece() {
			let data = {
				event_id: this.event.id,
				piece_code: this.actionInput,
			};
			router.put(route('events.checkinPiece', this.event.id), data);
		},
		completePiece() {
			console.debug('TODO: complete piece');
		},
		itemAction() {
			switch (this.actionValue) {
			case 'Allocate':
				this.allocatePieceCode();
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
		checkCodeInput(event) {
			if (event.keyCode === 13) {
				this.itemAction();
			}
		},
		handleTableSelectionChange(val) {
			this.tableSelection.value = val;
		},
		getStatus(item_id) {
			let items = this.event.items.filter((item) => item.item_id === item_id);
			if (items.every((item) => item.status === 'allocated')) {
				return 'allocated';
			} else {
				return 'reserved';
			}
		},
		getAllocated(item_id) {
			let items = this.event.items.filter((item) => item.item_id === item_id);
			let allocatedItems = items.filter((item) => item.status === 'allocated');

			return allocatedItems.length;
		},
	},
};
</script>

<template>
	<MainLayout :title="event ? event.title : 'Events'" :errors="errors">
		<el-tabs v-model="activeTab">
			<el-tab-pane v-if="event" label="Items" name="items">
				<el-container class="items-pane-container" direction="vertical">
					<el-row justify="space-between" direction="horizontal" style="margin-bottom: 15px;">
						<el-container class="item-action-button-container">
							<el-button type="primary" @click="itemDialogVisible = true">Add</el-button>
							<el-button type="primary" @click="removeItems" :disabled="!tableSelection.value?.length">Remove {{ tableSelection.value?.length ? '(' + tableSelection.value.length + ')' : '' }}</el-button>
						</el-container>
						<el-container class="item-action-dropdown">
							<el-dropdown split-button type="primary" trigger="click" @click="itemAction">
								{{ actionValue }}
								<template #dropdown>
									<el-dropdown-item @click="actionValue='Allocate'">Allocate</el-dropdown-item>
									<el-dropdown-item @click="actionValue='Check-Out'">Check-Out</el-dropdown-item>
									<el-dropdown-item @click="actionValue='Check-In'">Check-In</el-dropdown-item>
									<el-dropdown-item @click="actionValue='Complete'">Complete</el-dropdown-item>
								</template>
							</el-dropdown>
							<el-input class="item-action-input" v-model="actionInput" placeholder="Enter Item Code" @keypress="checkCodeInput" />
						</el-container>
					</el-row>
					<el-table :data="augmentedItems" height="100%" @selection-change="handleTableSelectionChange">
						<el-table-column type="selection" width="55" />
						<el-table-column prop="item_title" label="Title" sortable />
						<el-table-column label="Quantity">
							<template #default="scope">
								<div>{{ itemQuantities[scope.row.item_id].stock_type === 'hire' ? 1 : itemQuantities[scope.row.item_id].quantity }}</div>
							</template>
						</el-table-column>
						<el-table-column label="Allocated">
							<template #default="scope">
								<div v-if="scope.row.item_stock_type === 'hire'">{{ scope.row.piece_id ? 1 : 0 }}</div>
								<div v-else>{{ getAllocated(scope.row.item_id) }}</div>
							</template>
						</el-table-column>
						<el-table-column prop="piece_code" label="Code">
							<template #default="scope">
								<div v-if="scope.row.piece_code">{{scope.row.piece_code}}</div>
								<div v-else-if="scope.row.item_stock_type === 'bulk'">Bulk Stock</div>
							</template>
						</el-table-column>
						<el-table-column prop="status" label="Status">
							<template #default="scope">
								<div v-if="scope.row.item_stock_type === 'hire'">{{ scope.row.status }}</div>
								<div v-else>{{getStatus(scope.row.item_id)}}</div>
							</template>
						</el-table-column>
						<el-table-column label="Action">
							<template #default="scope">
								<el-dropdown trigger="click" size="small">
									<el-button type="primary"><el-icon><arrow-down /></el-icon></el-button>
									<template #dropdown>
										<el-dropdown-menu>
											<el-dropdown-item @click="rowAllocate(scope.row)">Allocate</el-dropdown-item>
										</el-dropdown-menu>
									</template>
								</el-dropdown>
							</template>
						</el-table-column>
					</el-table>
				</el-container>
			</el-tab-pane>
			<el-tab-pane label="Information" name="information">
				<CreateLayout :existing="event" :changes="formChanges" @remove="remove" @openIndex="openIndex" @save="save">
					<template #form>
						<el-container direction="vertical">
							<el-text class="form-title" size="large" tag="b">Details</el-text>
							<EventForm ref="eventForm" :event="event" @change="(e) => formChanges=e" />
						</el-container>
					</template>
					<template #default>
						<el-col class="customer-index-list" :xs="24" :sm="24" :md="24" :lg="16" :xl="16" v-if="event" direction="vertical">
							<el-text size="large" tag="b">Customers</el-text>
							<el-container class="customer-item-wrapper">
								<el-scrollbar class="customer-scrollbar" height="100%">
									<el-container class="list-space">
										<el-card class="customer-item add-card" shadow="hover" @click="customerDialogVisible = true">
											<el-text tag="b" size="large">Add Customer</el-text><el-icon><Plus /></el-icon>
										</el-card>
										<CustomerItem v-for="customer in event.customers" :key="customer.id" :customer="customer" :remove="true" @removeCustomer="removeCustomer" />
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

			.items-pane-container {
				height: 100%;

				.item-action-button-container {
					button {
						width: 130px;
					}
				}

				.item-action-dropdown {
					flex: unset;

					.el-dropdown {
						width: 100%;
					}

					.item-action-input {
						.el-input__wrapper {
							max-width: 25%;
							min-width: 315px;
						}
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