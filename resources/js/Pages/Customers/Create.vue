<script>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';

export default {
	props: {
		customer: Object,
		errors: Object,
		customerEvents: Array,
	},
	data() {
		return {
			customerForm: reactive({
				name: this.customer ? this.customer.name : '',
				email: this.customer ? this.customer.email : '',
				phone_number: this.customer ? this.customer.phone_number : '',
				company: this.customer ? this.customer.company : '',
			}),
			rules: reactive({
				name: [
					{ required: true, message: 'Name is required', trigger: 'blur', },
				],
				email: [
					{required: true, message: 'Email is required', trigger: 'blur', },
					{type: 'email', message: 'Valid email is required',},
				],
				phone_number: [
					{ required: true, message: 'Phone Number is required', trigger: 'blur', },
				],
			}),
		};
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
		save() {
			this.validate()
				.then((valid) => {
					if (valid) {
						if (this.customer) {
							router.patch(route('customers.update', this.customer.id), this.customerForm);
						} else {
							router.post(route('customers.store', this.customerForm));
						}
					}
				});
		},
		remove() {
			ElMessageBox.confirm(
				'Are you sure you want to permanently delete this user?',
				'Delete User',
				{
					confirmButtonText: 'Delete',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.delete(route('customers.destroy', this.customer.id));
			}).catch(() => {});
		},
		openIndex() {
			router.get(route('customers.index'));
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
	<MainLayout title="Customers">
		<el-container class="customer-container">
			<el-col :xs="24" :sm="24" :md="24" :lg="8" :xl="8">
				<el-row style="height:100%;">
					<el-form class="customer-info-form" ref="formRef" label-position="top" :model="customerForm" :rules="rules">
						<el-form-item label="Name" prop="name" required>
							<el-input v-model="customerForm.name"/>
						</el-form-item>
						<el-form-item label="Email" prop="email" required>
							<el-input v-model="customerForm.email"/>
						</el-form-item>
						<el-form-item label="Phone Number" prop="phone_number" required>
							<el-input v-model="customerForm.phone_number"/>
						</el-form-item>
						<el-form-item label="Company" prop="company">
							<el-input v-model="customerForm.company"/>
						</el-form-item>
					</el-form>
					<el-row style="width:100%;align-items:flex-end;" justify="space-between">
						<el-col :span="8">
							<el-button v-if="customer" type="danger" @click="remove">Delete</el-button>
						</el-col>
						<el-col style="text-align:right;" :span="16">
							<el-button type="primary" @click="openIndex">Cancel</el-button>
							<el-button type="primary" @click="save">{{customer ? 'Save' : 'Create'}}</el-button>
						</el-col>
					</el-row>
				</el-row>
			</el-col>
			<el-divider class="customer-info-divider" direction="vertical"/>
			<el-col class="event-index-list" :xs="24" :sm="24" :md="24" :lg="16" :xl="16" direction="vertical">
				<el-text size="large" tag="b">Events</el-text>
				<el-container class="event-item-wrapper">
					<el-scrollbar class="event-scrollbar" height="100%">
						<el-container class="list-space">
							<EventItem v-for="event in customerEvents" :key="event.id" :event="event" />
						</el-container>
					</el-scrollbar>
				</el-container>
			</el-col>
		</el-container>
	</MainLayout>
</template>
<style lang="scss">
.customer-container {
	height: 100%;

	.customer-info-form {
		width: 100%;
	}

	.customer-info-divider {
		height: 100%;

		border-color: black;

		margin: 0 15px;
	}

	.event-index-list {
		flex: initial !important;
		width: 100%;

		margin-bottom: 1em;

		.event-item-wrapper {
			height: 100%;
			overflow: hidden;

			.event-scrollbar {
				margin-top: 1em;

				width: 100%;
			}

			.list-space {
				display: grid !important;
				grid-gap: 15px;
				grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));

				height: auto;

				margin-bottom: 1em;
			}
		}
	}
}
</style>