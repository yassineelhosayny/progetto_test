<script setup lang="ts">
	interface Props {
		block: {
			type: string;
			data: {
				banner_editor: string;
				banner_image_1: string;
				banner_image_2: string;
			};
		};
	}	

	const props = defineProps<Props>();
</script>
<template>
	<div class="banner-block custom-container shadow-lg bg-white rounded-lg">
		<div class="p-8">
			<h4 v-html="props.block.data.banner_editor"></h4>
			<div class="images flex gap-4 mt-4">
				<img v-if="props.block.data.banner_image_1" :src="`/storage/${props.block.data.banner_image_1}`" alt="Banner Image 1" class="h-48 object-cover"/>
				<img v-if="props.block.data.banner_image_2" :src="`/storage/${props.block.data.banner_image_2}`" alt="Banner Image 2" class="h-48 object-cover"/>
			</div>
		</div>
	</div>
</template>

