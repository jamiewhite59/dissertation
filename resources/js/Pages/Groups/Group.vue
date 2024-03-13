<script>
import { router } from '@inertiajs/vue3';

import { ElMessageBox } from 'element-plus';

export default {
	props: {
		group: Object,
		errors: Object,
	},
	data() {
		return {
			formChanges: false,
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
	},
};
</script>
<template>
	<MainLayout :title="group ? group.title : 'Group'" :errors="errors">
		<CreateLayout :existing="group" :changes="formChanges" @remove="remove" @openIndex="openIndex" @save="save">
			<template #form>
				<el-container direction="vertical">
					<el-text size="large" tag="b">Details</el-text>
					<GroupForm ref="groupForm" :group="group" @change="(e) => typeof(e) === 'boolean' ? formChanges=e : ''" />
				</el-container>
			</template>
		</CreateLayout>
	</MainLayout>
</template>
<style lang="scss">

</style>