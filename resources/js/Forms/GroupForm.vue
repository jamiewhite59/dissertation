<script>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';

export default {
	props: {
		group: Object,
	},
	data() {
		return {
			groupForm: reactive({
				title: this.group ? this.group.title : '',
				container_piece_code: this.group?.container ? this.group.container.code : '',
			}),
			rules: reactive({
				title: [
					{ required: true, message: 'Title is required', trigger: 'blur', },
				],
				container_piece_code: [
					{ required: true, message: 'Code is required', trigger: 'blur', },
				],
			}),
		};
	},
	computed: {
		changes() {
			if (this.group) {
				return Object.keys(this.groupForm).some((key) => this.group[key] !== this.groupForm[key]);
			} else {
				return true;
			}
		},
	},
	methods: {
		save() {
			this.validate()
				.then((valid) => {
					if (valid) {
						if (this.group) {
							router.patch(route('groups.update', this.group.id), this.groupForm);
						} else {
							router.post(route('groups.store', this.groupForm));
						}
					}
				});
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
			this.groupForm.title = '';
			this.groupForm.container_piece_code = '';
			this.$refs.formRef.resetFields();
		},
	},
};
</script>
<template>
	<el-form class="create-form" ref="formRef" label-position="top" :model="groupForm" :rules="rules" @input="$emit('change', changes)">
		<el-form-item label="Title" prop="title" required>
			<el-input v-model="groupForm.title" ref="groupTitle"/>
		</el-form-item>
		<el-form-item label="Container Piece Code" prop="container_piece_code">
			<el-input v-model="groupForm.container_piece_code" :disabled="!!group" required/>
		</el-form-item>
	</el-form>
</template>
<style lang="scss">

</style>