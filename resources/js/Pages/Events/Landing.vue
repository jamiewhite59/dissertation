<script>
import { Head } from '@inertiajs/vue3';

import MainLayout from '@/Layouts/MainLayout.vue';

export default {
	components: {
		Head,
		MainLayout,
	},
	props: {
		name: String,
	},

	methods: {
		create() {
			console.debug('this is a test');
		}
	}
}

</script>

<template>
    <Head title="Events" />

	<MainLayout title="Hello there">
		<div>Events Landing</div>
	</MainLayout>
</template>
