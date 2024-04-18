<script>
import { router } from '@inertiajs/vue3';
import { ElMessageBox, ElMessage } from 'element-plus';

export default {
	props: {
		event: Object,
		customers: Array,
		items: Array,
		groups: Array,
		categories: Array,
		errors: Object,
		flash: Object,
	},
	data() {
		return {
			customerDialogVisible: false,
			itemDialogVisible: false,
			customerSearch: '',
			itemSearch: '',
			activeTab: this.event ? 'items' : 'information',
			activeDialogTab: 'hire',
			bulkDialogQuantity: 1,
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
				return availableCustomers.filter((customer) => {
					let search = this.customerSearch.toLowerCase();
					return customer.name.toLowerCase().includes(search)
					|| customer.email.toLowerCase().includes(search)
					|| customer.phone_number.toLowerCase().includes(search);

				});
			} else {
				return availableCustomers;
			}
		},
		filteredItems() {
			if (this.itemSearch) {
				return this.items.filter((item) => {
					let search = this.itemSearch.toLowerCase();
					return item.title.toLowerCase().includes(search)
					|| item.category?.title.toLowerCase().includes(search);
				});
			} else {
				return this.items;
			}
		},
		filteredHireItems() {
			return this.filteredItems?.filter((item) => item.stock_type === 'hire');
		},
		filteredBulkItems() {
			return this.filteredItems?.filter((item) => item.stock_type === 'bulk');
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
		eventItemCategories() {
			let categories = this.augmentedItems?.map((item) => {
				let category = this.categories.find((category) => {
					return item.item_category_id === category.id;
				});
				if (! category.children) {
					category.children = [];
				}
				category.children.push(item);
				return category;
			});
			return [... new Set(categories),];
		},
		groupsItemInfo() {
			let groupItemInfo = [];

			this.groups.forEach((group) => {
				let groupInfo = {
					group_id: group.id,
					items: [],
				};
				group.pieces.forEach((piece) => {
					let item = groupInfo.items.find((item) => item.id === piece.item_id);
					if (! item)  {
						piece.item.quantity = 1;
						groupInfo.items.push(piece.item);
					} else {
						item.quantity++;
					}
				});
				groupItemInfo.push(groupInfo);
			});
			return groupItemInfo;
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
	watch: {
		success() {
			console.debug('success?!', this.success);
		},
		actionValue() {
			this.$refs.actionInput.focus();
			setTimeout(() => {
				this.$refs.actionInput.focus();
			}, 30);
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
		addItem(item) {
			let data = {
				event_id: this.event.id,
				item_id: item.id,
				quantity: this.bulkDialogQuantity,
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
		allocatePieceCode(item, code) {
			let containerGroup = this.groups.find((group) => {
				return group.container.code === this.actionInput;
			});
			if (containerGroup) {
				this.addGroup(containerGroup);
			} else {
				let data = {
					event_id: this.event.id,
					piece_code: code ? code : this.actionInput,
					event_item_id: item?.id,
				};
				router.put(route('events.addItemPiece', this.event.id), data);
			}
			this.actionInput = '';
		},
		allocatePieceBulk(item, quantity) {
			let data = {
				event_id: item.event_id,
				item_id: item.item_id,
				quantity: quantity,
			};
			router.put(route('events.allocateBulk', item.event_id), data);
		},
		actionBulk(item, quantity, old_status, new_status) {
			let data = {
				event_id: item.event_id,
				item_id: item.item_id,
				quantity: quantity,
				old_status: old_status,
				new_status: new_status,
			};

			router.put(route('events.actionBulk', item.event_id), data);
		},
		rowAction(item, action) {
			if (item.item_stock_type === 'hire') {
				switch (action) {
				case 'allocate':
					ElMessageBox.prompt('Input piece code', 'Code', {
						confirmButtonText: 'Allocate',
						cancelButtonText: 'Cancel',
					}).then((value) => {
						this.allocatePieceCode(item, value.value);
					});
					return;
				case 'check-out':
					this.checkoutPiece(item.piece_code);
					return;
				case 'check-in':
					this.checkinPiece(item.piece_code);
					return;
				case 'complete':
					this.completePiece(item.piece_code);
					return;
				default:
					console.warn('Definitely shouldnt be here mate');
				}
			} else {
				ElMessageBox.prompt('Input quantity', 'Quantity', {
					confirmButtonText: 'Confirm',
					cancelButtonText: 'Cancel',
				}).then((value) => {
					switch (action) {
					case 'allocate':
						this.allocatePieceBulk(item, value.value);
						return;
					case 'check-out':
						this.actionBulk(item, value.value, 'allocated', 'checked-out');
						return;
					case 'check-in':
						this.actionBulk(item, value.value, 'checked-out', 'checked-in');
						return;
					case 'complete':
						this.actionBulk(item, value.value, 'checked-in', 'completed');
						return;
					}
				});
			}
		},
		checkoutPiece(code) {
			let data = {
				event_id: this.event.id,
				piece_code: code || this.actionInput,
			};
			let eventItem = this.event.items.find((piece) => piece.piece_code === data.piece_code);
			if (! eventItem || ['checked-out', 'completed',].includes(eventItem?.status)) {
				this.statusChangeErrMessage();
				return;
			}
			if (eventItem.status !== 'reserved' && eventItem.status !== 'allocated') {
				ElMessageBox.confirm(
					'This item is not currently reserved or allocated, do you want to force its status?',
					'Force Status',
					{
						confirmButtonText: 'Confirm',
						type: 'warning',
						center: true,
					}
				).then(() => {
					router.put(route('events.checkoutPiece', this.event.id), data);
				}).catch(() => {});
			} else {
				router.put(route('events.checkoutPiece', this.event.id), data);
			}
		},
		checkinPiece(code) {
			let data = {
				event_id: this.event.id,
				piece_code: code || this.actionInput,
			};
			let eventItem = this.event.items.find((piece) => piece.piece_code === data.piece_code);
			if (! eventItem || ['checked-in', 'completed',].includes(eventItem?.status)) {
				this.statusChangeErrMessage();
				return;
			}
			if (eventItem.status !== 'checked-out') {
				ElMessageBox.confirm(
					'This item is not currently checked-out, do you want to force its status?',
					'Force Status',
					{
						confirmButtonText: 'Confirm',
						type: 'warning',
						center: true,
					}
				).then(() => {
					router.put(route('events.checkinPiece', this.event.id), data);
				}).catch(() => {});
			} else {
				router.put(route('events.checkinPiece', this.event.id), data);
			}
		},
		completePiece(code) {
			let data = {
				event_id: this.event.id,
				piece_code: code || this.actionInput,
			};
			let eventItem = this.event.items.find((piece) => piece.piece_code === data.piece_code);
			if (! eventItem || (eventItem?.status === 'completed')) {
				this.statusChangeErrMessage();
				return;
			}
			if (eventItem.status !== 'checked-in') {
				ElMessageBox.confirm(
					'This item is not currently checked-in, do you want to force its status?',
					'Force Status',
					{
						confirmButtonText: 'Confirm',
						type: 'warning',
						center: true,
					}
				).then(() => {
					router.put(route('events.completePiece', this.event.id), data);
				}).catch(() => {});
			} else {
				router.put(route('events.completePiece', this.event.id), data);
			}
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
				this.actionInput = '';
			}
		},
		handleTableSelectionChange(val) {
			this.tableSelection.value = val;
		},
		getBulkStatus(item_id) {
			if (! item_id) {
				return '';
			}
			let items = this.event.items.filter((item) => item.item_id === item_id);
			if (items.every((item) => item.status === 'completed')) {
				return 'completed';
			} else if (items.every((item) => item.status === 'checked-in') || (items.some((item) => item.status === 'completed') && ! items.every((item) => item.status === 'completed'))) {
				return 'checked-in';
			} else if (items.every((item) => item.status === 'checked-out') || (items.some((item) => item.status === 'checked-in') && ! items.every((item) => item.status === 'checked-in'))) {
				return 'checked-out';
			} else if (items.every((item) => item.status === 'allocated') || (items.some((item) => item.status === 'checked-out') && ! items.every((item) => item.status === 'checked-out'))) {
				return 'allocated';
			} else {
				return 'reserved';
			}
		},
		getStatusQuantity(item_id, status) {
			if (! item_id) {
				return '';
			}
			let items = this.event.items.filter((item) => item.item_id === item_id);
			let statusArr = ['allocated', 'checked-out', 'checked-in', 'completed',];
			let index = statusArr.indexOf(status);
			return items.filter((item) => statusArr.slice(index).includes(item.status)).length;
		},
		rowDropdownDisabled(row, action) {
			switch (action) {
			case 'allocate':
				return row.item_stock_type === 'hire' ? row.status !== 'reserved' : this.getBulkStatus(row.item_id) !== 'reserved';
			case 'check-out':
				return row.item_stock_type === 'hire' ? ['checked-out', 'reserved', 'completed',].includes(row.status) : ['reserved', 'checked-out', 'checked-in', 'completed',].includes(this.getBulkStatus(row.item_id));
			case 'check-in':
				return row.item_stock_type === 'hire' ? ['checked-in', 'reserved', 'completed',].includes(row.status) : [ 'reserved', 'checked-in', 'completed',].includes(this.getBulkStatus(row.item_id));
			case 'complete':
				return row.item_stock_type === 'hire' ? ['completed', 'reserved',].includes(row.status) : ['completed', 'reserved',].includes(this.getBulkStatus(row.item_id));
			}
		},
		statusChangeErrMessage() {
			ElMessage.error({
				dangerouslyUseHTMLString: true,
				message: '<strong>Error: </strong>' + 'Unable to change status.',
				grouping: true,
			});
		},
		openEdit(piece, col) {
			if (col.property === 'title' && ! piece.children) {
				router.get(route('items.edit', piece.item_id));
			}
		},
		mouseEnter(row, col, cell, e) {
			if (col.property === 'title' && ! row.children) {
				cell.style.cursor = 'pointer';
			}
		},
		addGroup(group) {
			router.put(route('events.addGroup', this.event.id), group);
		},
		groupItems(id) {
			return this.groupsItemInfo.find((group) => {
				return group.group_id === id;
			}).items;
		},
		toggleCategoryExpanded(row) {
			this.$refs.itemTable.toggleRowExpansion(row);
		},
	},
};
</script>

<template>
	<MainLayout :title="event ? event.title : 'Events'" :errors="errors" :flash="flash">
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
							<el-input class="item-action-input" ref="actionInput" v-model="actionInput" placeholder="Enter Item Code" @keypress="checkCodeInput" />
						</el-container>
					</el-row>
					<el-table :data="eventItemCategories" ref="itemTable" height="100%" :default-sort="{prop: 'title', order:'ascending'}" row-key="id" @selection-change="handleTableSelectionChange" @cell-click="openEdit" @cell-mouse-enter="mouseEnter" @row-click="toggleCategoryExpanded">
						<el-table-column type="selection" width="55" />
						<el-table-column prop="title" label="Item/Category">
							<template #default="scope">
								<el-text v-if="scope.row.children" tag="b">{{ scope.row.title }}</el-text>
								<el-text v-else>{{ scope.row.title }}</el-text>
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
								<div v-else>{{getBulkStatus(scope.row.item_id)}}</div>
							</template>
						</el-table-column>
						<el-table-column label="Qty" width="60">
							<template #default="scope">
								<div>{{ itemQuantities[scope.row.item_id]?.stock_type === 'hire' ? 1 : itemQuantities[scope.row.item_id]?.quantity }}</div>
							</template>
						</el-table-column>
						<el-table-column label="Alloc" width="60">
							<template #default="scope">
								<div v-if="scope.row.item_stock_type === 'hire'">{{ scope.row.piece_id ? 1 : 0 }}</div>
								<div v-else>{{ getStatusQuantity(scope.row.item_id, 'allocated') }}</div>
							</template>
						</el-table-column>
						<el-table-column label="Out" width="60">
							<template #default="scope">
								<div v-if="scope.row.item_stock_type === 'hire'">{{ ['checked-out', 'checked-in', 'completed'].includes(scope.row.status) ? 1 : 0 }}</div>
								<div v-else>{{ getStatusQuantity(scope.row.item_id, 'checked-out') }}</div>
							</template>
						</el-table-column>
						<el-table-column label="In" width="60">
							<template #default="scope">
								<div v-if="scope.row.item_stock_type === 'hire'">{{ ['checked-in', 'completed'].includes(scope.row.status) ? 1 : 0 }}</div>
								<div v-else>{{ getStatusQuantity(scope.row.item_id, 'checked-in') }}</div>
							</template>
						</el-table-column>
						<el-table-column label="Compl" width="70">
							<template #default="scope">
								<div v-if="scope.row.item_stock_type === 'hire'">{{ scope.row.status === 'completed' ? 1 : 0 }}</div>
								<div v-else>{{ getStatusQuantity(scope.row.item_id, 'completed') }}</div>
							</template>
						</el-table-column>
						<el-table-column label="Action" width="90">
							<template #default="scope">
								<el-dropdown v-if="! scope.row.children" trigger="click" size="small">
									<el-button type="primary"><el-icon><arrow-down /></el-icon></el-button>
									<template #dropdown>
										<el-dropdown-menu>
											<el-dropdown-item :disabled="rowDropdownDisabled(scope.row, 'allocate')" @click="rowAction(scope.row, 'allocate')">Allocate</el-dropdown-item>
											<el-dropdown-item :disabled="rowDropdownDisabled(scope.row, 'check-out')" @click="rowAction(scope.row, 'check-out')">Check Out</el-dropdown-item>
											<el-dropdown-item :disabled="rowDropdownDisabled(scope.row, 'check-in')" @click="rowAction(scope.row, 'check-in')">Check In</el-dropdown-item>
											<el-dropdown-item :disabled="rowDropdownDisabled(scope.row, 'complete')" @click="rowAction(scope.row, 'complete')">Complete</el-dropdown-item>
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
							<el-text size="large" tag="b">Details</el-text>
							<EventForm ref="eventForm" :event="event" @change="(e) => typeof(e) === 'boolean' ? formChanges=e : ''" />
						</el-container>
					</template>
					<template #default>
						<el-col class="customer-index-list" :xs="24" :sm="24" :md="24" :lg="16" :xl="16" v-if="event" direction="vertical">
							<el-text size="large" tag="b">Customers ({{ event.customers.length }})</el-text>
							<el-container class="customer-item-wrapper">
								<el-scrollbar class="customer-scrollbar" height="100%">
									<el-container class="list-space">
										<AddCard addItem="Customer" @addClicked="customerDialogVisible = true"/>
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
	<el-dialog v-model="customerDialogVisible" width="40%" style="min-height:400px;" align-center  @opened="$refs.customerSearch.focus()" @closed="customerSearch = ''">
		<template #header>Customers</template>
		<template #default>
			<el-input class="dialog-search" v-model="customerSearch" ref="customerSearch" placeholder="Search Customers" clearable />
			<el-table :data="filteredCustomers" height="250">
				<el-table-column label="Add" width="70">
					<template #default="scope">
						<el-button size="small" @click="addCustomer(scope.row.id)"><el-icon><Plus /></el-icon></el-button>
					</template>
				</el-table-column>
				<el-table-column prop="name" label="Name"/>
				<el-table-column prop="email" label="Email"/>
				<el-table-column prop="phone_number" label="Phone"/>
			</el-table>
		</template>
	</el-dialog>
	<el-dialog v-model="itemDialogVisible" width="40%" style="min-height:400px;" align-center @opened="$refs.itemSearch.focus()" @closed="itemSearch = ''">
		<template #header>Items</template>
		<template #default>
			<el-input class="dialog-search" v-model="itemSearch" ref="itemSearch" placeholder="Search Items" clearable />
			<el-tabs v-model="activeDialogTab">
				<el-tab-pane label="Hire" name="hire">
					<el-table :data="filteredHireItems" height="250">
						<el-table-column label="Add" width="70">
							<template #default="scope">
								<el-popover placement="left" trigger="hover" @after-leave="bulkDialogQuantity = 1">
									<el-text size="large" tag="b">Quantity</el-text>
									<el-container direction="horizontal">
										<el-input v-model="bulkDialogQuantity" type="number" min="1"/>
										<el-button @click="addItem(scope.row)" type="primary">Add</el-button>
									</el-container>
									<template #reference>
										<el-button size="small"><el-icon><Plus /></el-icon></el-button>
									</template>
								</el-popover>
							</template>
						</el-table-column>
						<el-table-column prop="title" label="Title"/>
						<el-table-column prop="category.title" label="Category"/>
						<el-table-column label="Quantity">
							<template #default="scope">
								<el-text>{{ scope.row.pieces.length }}</el-text>
							</template>
						</el-table-column>
					</el-table>
				</el-tab-pane>
				<el-tab-pane label="Bulk" name="bulk">
					<el-table :data="filteredBulkItems" height="250">
						<el-table-column label="Add" width="70">
							<template #default="scope">
								<el-popover placement="left" trigger="hover" @after-leave="bulkDialogQuantity = 1">
									<el-text size="large" tag="b">Quantity</el-text>
									<el-container direction="horizontal">
										<el-input v-model="bulkDialogQuantity" type="number" min="1"/>
										<el-button @click="addItem(scope.row)" type="primary">Add</el-button>
									</el-container>
									<template #reference>
										<el-button size="small"><el-icon><Plus /></el-icon></el-button>
									</template>
								</el-popover>
							</template>
						</el-table-column>
						<el-table-column prop="title" label="Title"/>
						<el-table-column prop="category.title" label="Category"/>
						<el-table-column label="Quantity">
							<template #default="scope">
								<el-text>{{ scope.row.pieces.length }}</el-text>
							</template>
						</el-table-column>
					</el-table>
				</el-tab-pane>
				<el-tab-pane label="Group" name="group">
					<el-table :data="groups" height="250">
						<el-table-column label="Add" width="70">
							<template #default="scope">
								<el-button size="small" @click="addGroup(scope.row)"><el-icon><Plus /></el-icon></el-button>
							</template>
						</el-table-column>
						<el-table-column prop="title" label="Title"/>
						<el-table-column label="Information">
							<template #default="scope">
								<el-popover placement="bottom" trigger="hover" width="300">
									<template #reference>
										<el-icon><InfoFilled /></el-icon>
									</template>
									<el-descriptions direction="vertical" :column="1" size="small">
										<el-descriptions-item v-for="item in groupItems(scope.row.id)" :key="item.id">
											<el-text>{{ item.title }} ({{ item.quantity }})</el-text>
										</el-descriptions-item>
									</el-descriptions>
								</el-popover>
							</template>
						</el-table-column>
					</el-table>
				</el-tab-pane>
			</el-tabs>
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