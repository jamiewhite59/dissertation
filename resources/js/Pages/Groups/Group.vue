<script>
import { router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { ElMessageBox } from 'element-plus';

export default {
	props: {
		group: Object,
		errors: Object,
		flash: Object,
	},
	data() {
		return {
			formChanges: false,
			pieceDialogVisible: false,
			pieceForm: reactive({
				pieceCode: '',
			}),
			rules: reactive({
				pieceCode: [
					{ required: true, message: 'Code is required', trigger: 'blur', },
				],
			}),
		};
	},
	methods: {
		remove() {
			ElMessageBox.confirm(
				'Are you sure you want to permanently delete this group?',
				'Delete Group',
				{
					confirmButtonText: 'Delete',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.delete(route('groups.destroy', this.group.id));
			}).catch(() => {});
		},
		openIndex() {
			router.get(route('groups.index'));
		},
		save() {
			this.$refs.groupForm.save();
		},
		validatePieceForm() {
			return this.$refs.pieceForm.validate()
				.then((valid) => {
					return true;
				})
				.catch((err) => {
					return false;
				});
		},
		addPiece() {
			this.validatePieceForm()
				.then((valid) => {
					if (valid) {
						router.put(route('groups.addPiece', this.group.id), {piece_code: this.pieceForm.pieceCode,});
						this.pieceForm.pieceCode = '';
					}
					this.$refs.pieceSearch.focus();
				});
		},
		removePiece(id) {
			ElMessageBox.confirm(
				'Are you sure you want to remove this piece from this group?',
				'Remove Item Piece',
				{
					confirmButtonText: 'Remove',
					type: 'error',
					center: true,
				}
			).then(() => {
				router.put(route('groups.removePiece', this.group.id), {id: id,});
			}).catch(() => {});
		},
		openItem(piece) {
			router.get((route('items.edit', piece.item.id)));
		},
		hideDialog() {
			this.pieceDialogVisible = false;
			this.pieceForm.piece_code = '';
			this.$refs.pieceForm.resetFields();
		},
	},
};
</script>
<template>
	<MainLayout :title="group ? group.title : 'Group'" :errors="errors" :flash="flash">
		<CreateLayout :existing="group" :changes="formChanges" @remove="remove" @openIndex="openIndex" @save="save">
			<template #form>
				<el-container direction="vertical">
					<el-text size="large" tag="b">Details</el-text>
					<GroupForm ref="groupForm" :group="group" @change="(e) => typeof(e) === 'boolean' ? formChanges=e : ''" />
				</el-container>
			</template>
			<template #default>
				<el-col class="pieces-content">
					<el-text size="large" tag="b" style="margin-bottom: 1em;">Pieces ({{ group.pieces.length }})</el-text>
					<el-container class="piece-wrapper">
						<el-scrollbar class="piece-scrollbar" height="100%">
							<el-container class="list-space">
								<AddCard class="piece-item" addItem="Piece" @addClicked="pieceDialogVisible = true"/>
								<PieceItem v-for="piece in group.pieces" :key="piece.id" :piece="piece" showTitle @click="openItem(piece)" @removePiece="removePiece"/>
							</el-container>
						</el-scrollbar>
					</el-container>
				</el-col>
			</template>
		</CreateLayout>
	</MainLayout>
	<el-dialog v-model="pieceDialogVisible" width="30%" style="min-height:150px;" align-center @opened="$refs.pieceSearch.focus()" @closed="hideDialog">
		<template #header>Add Piece</template>
		<template #default>
			<el-form ref="pieceForm" label-position="top" :model="pieceForm" :rules="rules" @keyup.enter="addPiece">
				<el-form-item label="Piece Code" prop="pieceCode" required>
					<el-input v-model="pieceForm.pieceCode" ref="pieceSearch" placeholder="Enter Piece Code" clearable >
						<template #append>
							<el-button @click="addPiece">Add</el-button>
						</template>
					</el-input>
				</el-form-item>
				<el-form-item style="display:none">
					<el-input/>
				</el-form-item>
			</el-form>
		</template>
	</el-dialog>
</template>
<style lang="scss">
.pieces-content {
	flex: 1;
	height: 100%;

	text-align: center;

	.piece-wrapper {
		height: calc(100% - 36px);

		.piece-scrollbar {
			margin-top: 1em;
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