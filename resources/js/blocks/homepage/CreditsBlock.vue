<script setup lang="ts">

interface Props {
	block: {
		type: string;
		data: {
			image_1: string;
			image_2: string;
			title: string;
			description: string;
		};
	};
}

const props = defineProps<Props>();
</script>

<template>
	<div class="credits-block custom-container bg-gray-200 text-black">
		<div class="p-8 flex flex-col md:flex-row items-center md:items-start gap-6">
			<img v-if="props.block.data.image_1" :src="`/storage/${props.block.data.image_1}`" alt="Credit Image 1" class="h-32 md:h-48 object-cover"/>
			<div class="text-center md:text-left flex flex-col gap-4">
				<h4 class="text-2xl font-semibold mb-2" v-html="props.block.data.title"></h4>
				<p v-html="props.block.data.description"></p>
			</div>
			<img v-if="props.block.data.image_2" :src="`/storage/${props.block.data.image_2}`" alt="Credit Image 2" class="h-32 md:h-48 object-cover"/>
		</div>
	</div>
</template>
