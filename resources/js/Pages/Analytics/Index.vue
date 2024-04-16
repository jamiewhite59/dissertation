<script>
import { Bar, Pie, Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, Colors } from 'chart.js';
import { collapseItemProps } from 'element-plus';
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, Colors);

export default {
	components: {
		Bar,
		// Pie,
		Doughnut,
	},
	props: {
		errors: Object,
		flash: Object,
		items: Array,
		customers: Array,
	},
	data() {
		return {
			itemEventsOptions: {
				responsive: true,
				plugins: {
					legend: {
						display: false,
					},
					title: {
						display: true,
						text: 'Number of events each item is/has been assigned to.',
					},
					colors: {
						enabled: true,
					},
				},
			},
			customerEventsOptions: {
				responsive: true,
				plugins: {
					legend: {
						display: false,
					},
					title: {
						display: true,
						text: 'Number of events each customer is/has been assigned to.',
					},
					colors: {
						enabled: true,
					},
				},
			},
		};
	},
	computed: {
		itemLabels() {
			return this.items.map(item => item.title);
		},
		itemEvents() {
			return this.items.map(item => item.events.length);
		},
		itemEventsData(){
			return {
				labels: this.itemLabels,
				datasets: [ { data: this.itemEvents, }, ],
			};
		},
		customerLabels() {
			return this.customers.map(customer => customer.name);
		},
		customerEvents() {
			return this.customers.map(customer => customer.events.length);
		},
		customerEventsData() {
			return {
				labels: this.customerLabels,
				datasets: [ {
					data: this.customerEvents,
					backgroundColor: this.generateColors(this.customerLabels.length),
				},],
			};
		},
	},
	methods: {
		 generateColors(numColors) {
			const colors = [];
			let usedIndexes = [numColors,];
			let index = 0;
			for (let i = 0; i < numColors; i++) {
				const hue = (360 / numColors) * index;
				const color = `hsl(${hue}, 100%, 45%)`;
				colors.push(color);
				usedIndexes.push(index);
				index = this.nextIndex(index, usedIndexes, numColors);
			}
			return colors;
		},
		opposite(index, numColors) {
			let opposite = (index + (numColors / 2)) % numColors;
			return opposite;
		},
		nextIndex(index, usedIndexes, numColors) {
			let opposite = this.opposite(index, numColors);
			if (usedIndexes.includes(opposite)) {
				let val = (Math.max(opposite, index) - (numColors / 4));
				if (usedIndexes.includes(val)) {
					val++;
				}
				return val;
			} else {
				return opposite;
			}
		},
	},
};
</script>
<template>
	<MainLayout title="Analytics" :errors="errors" :flash="flash">
		<el-container direction="vertical" style="height:100%;">
			<el-row justify="center" align="middle" style="flex:1;">
				<div style="position: relative; width: 40%;">
					<Doughnut id="a-doughnut" :options="itemEventsOptions" :data="itemEventsData"/>
				</div>
			</el-row>
			<el-row justify="center" align="middle" style="flex:1;">
				<div style="position: relative; width: 60%;">
					<Bar id="a-bar" :options="customerEventsOptions" :data="customerEventsData"/>
				</div>
			</el-row>
		</el-container>
	</MainLayout>
</template>
<style lang="scss">

</style>