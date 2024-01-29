<script>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';


export default {
	props: {
		item: Object,
	},
	data() {
		return {
			itemForm: reactive({
				title: this.item ? this.item.title : '',
			}),
			rules: reactive({
				title: [
					{ required: true, message: 'Title is required', trigger: 'blur', },
				],
			}),
		};
	},
	methods: {
		remove() {
			console.debug('remove');
		},
		openIndex() {
			console.debug('openindex');
		},
		save() {
			console.debug('save');
		},
	},
};
</script>
<template>
	<MainLayout title="Items">
		<CreateLayout :existing="item" @remove="remove" @openIndex="openIndex" @save="save">
			<template #form>
				<el-form class="create-form" ref="formRef" label-position="top" :model="itemForm" :rules="rules">
					<el-form-item label="Title" prop="title" required>
						<el-input v-model="itemForm.title"/>
					</el-form-item>
				</el-form>
			</template>
			<template #default>
				<el-col>
					<el-text>CONTENT</el-text>
				</el-col>
			</template>
		</CreateLayout>
	</MainLayout>
</template>
<style lang="scss">

</style>