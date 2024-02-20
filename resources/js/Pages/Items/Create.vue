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
				item_id: this.item?.id,
				code: '',
			}),
			pieceRules: reactive({
				code: [
					{ required: true, message: 'Code is required', trigger: 'blur', },
				],
			}),
			dialogVisible: false,
			selectedPiece: null,
		};
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
		savePiece() {
			this.validatePieceForm()
				.then((valid) => {
					if (valid) {
						if (this.selectedPiece) {
							router.patch(route('items.updatePiece', this.selectedPiece.id), this.pieceForm);
						} else {
							router.put(route('items.createPiece', this.item.id), this.pieceForm);
						}
						this.dialogVisible = false;
					}
				});
		},
		destroyPiece(id) {
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
		selectPiece(piece) {
			this.selectedPiece = piece;
			this.pieceForm.code = piece.code;
			this.dialogVisible = true;
		},
		hideDialog() {
			this.dialogVisible = false;
			this.selectedPiece = null;
			this.pieceForm.code = '';
		},
	},
};
</script>
<template>
	<MainLayout title="Items" :errors="errors">
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
					<template v-if="item?.stock_type === 'hire'">
						<el-container>
							<el-text size="large" tag="b" style="margin-bottom: 1em;">Pieces</el-text>
						</el-container>
						<el-container class="item-piece-wrapper">
							<el-scrollbar class="piece-scrollbar" height="100%">
								<el-container class="list-space">
									<el-card class="piece-item add-card" shadow="hover" @click="dialogVisible = true">
										<el-text tag="b" size="large">Add Piece</el-text>
										<el-icon><Plus/></el-icon>
									</el-card>
									<PieceItem v-for="piece in pieces" :key="piece.id" :piece="piece" @click="selectPiece(piece)" @removePiece="destroyPiece" />
								</el-container>
							</el-scrollbar>
						</el-container>
					</template>
					<el-text v-else>Single piece with a quantity?</el-text>
				</el-col>
			</template>
		</CreateLayout>
	</MainLayout>
	<el-dialog v-model="dialogVisible" width="30%" align-center @closed="hideDialog">
		<template #header>{{ selectedPiece ? 'Edit Piece' : 'Create Piece' }}</template>
		<template #default>
			<el-form ref="pieceFormRef" label-position="top" :model="pieceForm" :rules="pieceRules">
				<el-form-item label="Identifying Code" prop="code">
					<el-input v-model="pieceForm.code"/>
				</el-form-item>
			</el-form>
		</template>
		<template #footer>
			<el-button type="primary" @click="hideDialog">Cancel</el-button>
			<el-button type="primary" @click="savePiece">{{ selectedPiece ? 'Save' : 'Create' }}</el-button>
		</template>
	</el-dialog>
</template>
<style lang="scss">
.item-content {
	flex: 1;

	.item-piece-wrapper {
		height: calc(100% - 36px);

		.piece-scrollbar {
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