<script>
import MainLayout from '@/Layouts/MainLayout.vue';

export default {
	components: {
		MainLayout,
	},
	props: {
		name: String,
	},

	methods: {
		create() {
			console.debug('this is a test');
		},
	},
};

</script>

<template>
	<MainLayout title="Events">
		<div>Events Landing</div>
	</MainLayout>
</template>
