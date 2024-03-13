<script>
export default {
	props: {
		groups: Array,
		errors: Object,
		flash: Object,
	},
	data() {
		return {
			search: '',
			createDialogVisible: false,
		};
	},
	computed: {
		searchedGroups() {
			if (this.search) {
				return this.groups.filter((group) => {
					return group.title.toLowerCase().includes(this.search.toLowerCase());
				});
			} else {
				return this.groups;
			}
		},
	},
	methods: {
		openCreate() {
			this.createDialogVisible = true;
		},
		createGroup() {
			this.$refs.groupForm.save();
			this.createDialogVisible = false;
		},
		resetForm() {
			this.$refs.groupForm.resetForm();
		},
	},
};
</script>
<template>
	<MainLayout title="Groups" :errors="errors" :flash="flash">
		<OverviewLayout title="Group" :displayCards="!!groups.length" @openCreate="openCreate">
			<template #search>
				<el-input class="overview-search" v-model="search" placeholder="Search Groups" clearable />
			</template>
			<template #cards>
				<el-container class="group-list-space">
					<GroupItem v-for="group in searchedGroups" :key="group.id" :group="group" />
				</el-container>
			</template>
		</OverviewLayout>
	</MainLayout>
	<el-dialog v-model="createDialogVisible" width="30%" align-center @closed="resetForm">
		<template #header>Create Group</template>
		<template #default>
			<GroupForm ref="groupForm"/>
		</template>
		<template #footer>
			<el-button type="primary" @click="createDialogVisible = false">Cancel</el-button>
			<el-button type="primary" @click="createGroup">Create</el-button>
		</template>
	</el-dialog>
</template>
<style lang="scss">
.group-list-space {
	display: grid !important;
	grid-gap: 15px;
	grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
}
</style>