<script>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';


export default {
	props: {
		item: Object,
		errors: Object,
		pieces: Array,
	},
	data() {
		return {
			itemForm: reactive({
				title: this.item ? this.item.title : '',
				description: this.item ? this.item.description : '',
				image: this.item ? this.item.image : null,
				stock_type: this.item ? this.item.stock_type : 'bulk',
			}),
			itemRules: reactive({
				title: [
					{ required: true, message: 'Title is required', trigger: 'blur', },
				],
				stock_type: [
					{ required: true, message: 'Stock Type is required', trigger: 'blur', },
				],
				code: [],
			}),
			pieceForm: reactive({
				item_id: this.item.id,
				code: '',
			}),
			pieceRules: reactive({
				code: [
					{ required: true, message: 'Code is required', trigger: 'blur', },
				],
			}),
			dialogVisible: false,
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
		remove() {
			ElMessageBox.confirm(
				'Are you sure you want to permanently delete this item?',
				'Delete Item',
				{
					confirmButtonText: 'Delete',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.delete(route('items.destroy', this.item.id));
			}).catch(() => {});
		},
		openIndex() {
			router.get(route('items.index'));
		},
		save() {
			this.validateItemForm()
				.then((valid) => {
					if (valid) {
						if (this.item){
							router.patch(route('items.update', this.item.id), this.itemForm);
						} else {
							router.post(route('items.store', this.itemForm));
						}
					}
				});
		},
		validateItemForm() {
			return this.$refs.itemFormRef.validate()
				.then((valid) => {
					return true;
				})
				.catch((err) => {
					return false;
				});
		},
		validatePieceForm() {
			return this.$refs.pieceFormRef.validate()
				.then((valid) => {
					return true;
				})
				.catch((err) => {
					return false;
				});
		},
		createPiece() {
			this.validatePieceForm()
				.then((valid) => {
					if (valid) {
						this.dialogVisible = false;
						console.debug('piece form', this.pieceForm);
						router.put(route('items.createPiece', this.item.id), this.pieceForm);
					}
				});
		},
		destroyPiece(id) {
			console.debug('destroying piece with id', id);
			ElMessageBox.confirm(
				'Are you sure you want to permanently delete this piece?',
				'Delete Piece',
				{
					confirmButtonText: 'Delete',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.delete(route('items.destroyPiece', id));
			}).catch(() => {});
		},
	},
};
</script>
<template>
	<MainLayout title="Items">
		<CreateLayout :existing="item" @remove="remove" @openIndex="openIndex" @save="save">
			<template #form>
				<el-form class="create-form" ref="itemFormRef" label-position="top" :model="itemForm" :rules="itemRules">
					<el-form-item label="Title" prop="title" required>
						<el-input v-model="itemForm.title"/>
					</el-form-item>
					<el-form-item label="Description" prop="description">
						<el-input v-model="itemForm.description"/>
					</el-form-item>
					<el-form-item label="Stock Type" prop="stock_type" required>
						<el-select v-model="itemForm.stock_type">
							<el-option label="Bulk" value="bulk"/>
							<el-option label="Hire" value="hire"/>
						</el-select>
					</el-form-item>
					<el-form-item label="Image" prop="image">
						<el-input v-model="itemForm.image"/>
					</el-form-item>
				</el-form>
			</template>
			<template #default>
				<el-col class="item-content" :xs="24" :sm="24" :md="24" :lg="16" :xl="16" direction="vertical">
					<el-container v-if="item.stock_type === 'hire'">
						<el-container>
							<el-button type="primary" @click="dialogVisible = true">Create piece</el-button>
							<div v-for="piece in pieces" :key="piece.id" @click="destroyPiece(piece.id)">{{ piece.code }}</div>
						</el-container>
					</el-container>
					<el-text v-else>Single piece with a quantity?</el-text>
				</el-col>
			</template>
		</CreateLayout>
	</MainLayout>
	<el-dialog v-model="dialogVisible" width="30%" align-center>
		<template #header>Create Piece</template>
		<template #default>
			<el-form ref="pieceFormRef" label-position="top" :model="pieceForm" :rules="pieceRules">
				<el-form-item label="Identifying Code" prop="code">
					<el-input v-model="pieceForm.code"/>
				</el-form-item>
			</el-form>
		</template>
		<template #footer>
			<el-button type="primary" @click="dialogVisible = false">Cancel</el-button>
			<el-button type="primary" @click="createPiece">Create</el-button>
		</template>
	</el-dialog>
</template>
<style lang="scss">
.item-content {
	flex: initial !important;
	width: 100%;

	margin-bottom: 1em;
}
</style>