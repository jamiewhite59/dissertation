<script setup>
import { Head } from '@inertiajs/vue3';

import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps(['name']);

function create() {
	console.debug('this is a test ');
}
</script>

<template>
    <Head title="Events" />

	<MainLayout title="Hello there">

	</MainLayout>
	<!-- <h2>
		Hi there
	</h2>
	<div>
		Hellooooooo
		<p>{{ name }}</p>
		<el-button type="primary" :icon="Plus" @click="create">Create Event</el-button>
	</div> -->

</template>
