<script>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { ElMessageBox } from 'element-plus';


export default {
	props: {
		item: Object,
		errors: Object,
	},
	data() {
		return {
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
			formChanges: false,
		};
	},
	methods: {
		remove() {
			ElMessageBox.confirm(
				'Are you sure you want to permanently delete this item and all pieces associated with it?',
				'Delete Item',
				{
					confirmButtonText: 'Delete',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.put(route('items.destroy', this.item.id), this.item);
			}).catch(() => {});
		},
		saveItem() {
			this.$refs.itemForm.save();
		},
		openIndex() {
			router.get(route('items.index'));
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
			this.$refs.pieceFormRef.resetFields();
		},
	},
};
</script>
<template>
	<MainLayout title="Items" :errors="errors">
		<CreateLayout :existing="item" :changes="formChanges" @remove="remove" @openIndex="openIndex" @save="saveItem">
			<template #form>
				<ItemForm ref="itemForm" :item="item" @change="(e) => typeof(e) === 'boolean' ? formChanges=e : ''" />
			</template>
			<template #default>
				<el-container direction="vertical">
					<el-row :class="item?.stock_type === 'hire' ? 'extra-information-row' : 'extra-information-full'">
						<el-col class="item-content">
							<el-container>
								<el-text size="large" tag="b" style="margin-bottom: 1em;">Events ({{ item?.events.length }})</el-text>
							</el-container>
							<el-container class="item-piece-wrapper">
								<el-scrollbar class="piece-scrollbar" height="100%">
									<el-container class="list-space">
										<EventItem v-for="event in item.events" :key="event.id" :event="event"/>
									</el-container>
								</el-scrollbar>
							</el-container>
						</el-col>
					</el-row>
					<template v-if="item?.stock_type === 'hire'">
						<el-divider class="item-information-divider"/>
						<el-row :class="item?.stock_type === 'hire' ? 'extra-information-row' : 'extra-information-full'">
							<el-col class="item-content">
								<el-container>
									<el-text size="large" tag="b" style="margin-bottom: 1em;">Pieces ({{ item?.pieces.length }})</el-text>
								</el-container>
								<el-container class="item-piece-wrapper">
									<el-scrollbar class="piece-scrollbar" height="100%">
										<el-container class="list-space">
											<el-card class="piece-item add-card" shadow="hover" @click="dialogVisible = true;">
												<el-text tag="b" size="large">Add Piece</el-text>
												<el-icon><Plus/></el-icon>
											</el-card>
											<PieceItem v-for="piece in item.pieces" :key="piece.id" :piece="piece" @click="selectPiece(piece)" @removePiece="destroyPiece" />
										</el-container>
									</el-scrollbar>
								</el-container>
							</el-col>
						</el-row>
					</template>
				</el-container>
			</template>
		</CreateLayout>
	</MainLayout>
	<el-dialog v-model="dialogVisible" width="30%" align-center @opened="$refs.codeInput.focus()" @closed="hideDialog">
		<template #header>{{ selectedPiece ? 'Edit Piece' : 'Create Piece' }}</template>
		<template #default>
			<el-form ref="pieceFormRef" label-position="top" :model="pieceForm" :rules="pieceRules">
				<el-form-item label="Identifying Code" prop="code">
					<el-input ref="codeInput" v-model="pieceForm.code"/>
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
.extra-information-row {
	height: calc(50% - 21px);
}
.extra-information-full {
	height: 100%;
}
.item-information-divider {
	width: 100%;
	margin: 20px 0;

	border-color: #aaa3;
	box-sizing: border-box;
	border-top-width: 2px;
}
.item-content {
	flex: 1;
	height: 100%;

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