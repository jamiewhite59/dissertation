<script>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';

export default {
	props: {
		customer: Object,
		errors: Object,
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
	<MainLayout title="Customers" :errors="errors">
		<CreateLayout :existing="customer" @remove="remove" @openIndex="openIndex" @save="save">
			<template #form>
				<el-form class="create-form" ref="formRef" label-position="top" :model="customerForm" :rules="rules">
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
			</template>
			<template #default>
				<el-col class="event-index-list" :xs="24" :sm="24" :md="24" :lg="16" :xl="16" direction="vertical">
					<el-container style="margin-bottom:1em;">
						<el-text size="large" tag="b">Events</el-text>
					</el-container>
					<el-container class="event-item-wrapper">
						<el-scrollbar class="event-scrollbar" height="100%">
							<el-container class="list-space">
								<EventItem v-for="event in customer.events" :key="event.id" :event="event" />
							</el-container>
						</el-scrollbar>
					</el-container>
				</el-col>
			</template>
		</CreateLayout>
	</MainLayout>
</template>
<style lang="scss">
.event-index-list {
	flex: 1;

	.event-item-wrapper {
		height: calc(100% - 36px);

		.event-scrollbar {
			width: 100%;

			.list-space {
				display: grid !important;
				grid-gap: 15px;
				grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
			}
		}
	}
}
</style>