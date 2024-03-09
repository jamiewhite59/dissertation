<script>
import Sidebar from '@/Components/Sidebar.vue';
import { Head } from '@inertiajs/vue3';
import { ElMessage, ElMessageBox } from 'element-plus';

export default {
	components: {
		Sidebar,
		Head,
	},
	props: {
		title: String,
		errors: Object,
		flash: Object,
	},
	watch: {
		errors() {
			if (Object.keys(this.errors).length) {
				Object.values(this.errors).forEach((err) => {
					ElMessage.error({
						dangerouslyUseHTMLString: true,
						message: '<strong>Error: </strong>' + err,
						grouping: true,
					});
				});
			}
		},
		flash() {
			if (this.flash.success) {
				ElMessage.success({
					dangerouslyUseHTMLString: true,
					message: '<strong>Success: </strong>' + this.flash.success,
					grouping: true,
				});
			}
		},
	},
	methods: {
		back() {
			window.history.back();
		},
	},
};
</script>

<template>
	<Head :title="title" />
	<div class="main-layout">
		<el-container class="main-container">
			<el-aside class="main-menu">
				<div class="logo-container">
					<img class="logo" src="../../logo.png">
				</div>
				<Sidebar/>
			</el-aside>
			<el-container class="main-content">
				<el-header class="main-header" height="100px">
					<el-row style="flex: 1;">
						<el-col :span="23">
							<el-button type="primary" link @click="back">
								<el-icon><ArrowLeft /></el-icon>
								Back
							</el-button>
							<el-row>
								<el-text class="page-title" size="large" tag="b">{{title}}</el-text>
							</el-row>
						</el-col>
						<el-col :span="1">
							<el-avatar class="user-avatar">
								<el-icon><UserFilled/></el-icon>
							</el-avatar>
						</el-col>
					</el-row>
				</el-header>
				<el-main class="main-main"><slot></slot></el-main>
			</el-container>
		</el-container>
	</div>
</template>

<style lang="scss">
.main-layout {
	height: 100%;

	.main-header {
		display: flex;

		justify-content: space-between;
		align-items: center;

		background-color: var(--el-color-white);

		.page-title {
			color: var(--el-color-black);
			font-size: 2em;
		}

		.user-avatar {
			.el-icon {
				height: 1.5em;
				width: 2em;

				svg {
					height: 2em;
					width: 2em;
				}
			}
		}
	}

	.main-main {
		background-color: rgb(224, 223, 223);
	}

	.main-container {
		height: 100%;

		.main-menu {
			height: 100%;
			width: 20%;
			min-width: 175px;

			background-color: var(--el-menu-bg-color);

			.logo-container {
				display: flex;
				justify-content: center;

				padding-top: 1em;
				padding-bottom: 1em;

				.logo {
					height: 75px;

					object-fit: cover;
				}
			}

			.el-menu {
				border-right: 0;

				.el-menu-item:hover {
					color: var(--el-menu-hover-text-color);
				}

				.is-disabled {
					color: var(--el-menu-text-color) !important;
				}

				.el-sub-menu__title:hover {
					color: var(--el-menu-hover-text-color) !important;
				}
			}
		}
	}
}
</style>