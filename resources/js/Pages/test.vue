<script>
import { reactive, ref } from 'vue';

export default {
	components: {
	},
	props: {
		errors: Object,
	},
	data() {
		return {
			customerForm: reactive({
				name: '',
			}),
			rules: reactive({
				name: [
					{required: true, message: 'required field',},
				],
			}),
		};
	},
	methods: {
		validate() {
			console.debug(this.$refs.formRef);
			this.$refs.formRef.validate((valid) => {
				console.debug('valid', valid);
			});
		},
	},

	mounted() {

	},
};
</script>

<template>
	<el-form ref="formRef" :model="customerForm" :rules="rules">
		<el-form-item label="Name" prop="name">
			<el-input v-model="customerForm.name"/>
		</el-form-item>
	</el-form>
	<el-button @click="validate">Validate</el-button>
</template>