<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  block: {
    type: string;
    data: {
      title?: string;
      description?: string;
      images?: string[];
    };
  };
}

const props = defineProps<Props>();

const images = computed(() => props.block.data.images?.filter(Boolean) ?? []);

const getImageUrl = (path: string): string => {
  if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('/')) {
    return path;
  }

  return `/storage/${path}`;
};
</script>

<template>
  <section class="gallery-block py-10">
    <div class="custom-container mx-auto px-4">
      <div v-if="props.block.data.title || props.block.data.description" class="mb-6 max-w-3xl">
        <h3 v-if="props.block.data.title" class="text-3xl font-semibold text-gray-950">
          {{ props.block.data.title }}
        </h3>
        <p v-if="props.block.data.description" class="mt-3 text-base leading-7 text-gray-600">
          {{ props.block.data.description }}
        </p>
      </div>

      <div v-if="images.length > 0" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <figure
          v-for="(image, index) in images"
          :key="`${image}-${index}`"
          class="overflow-hidden rounded-lg bg-gray-100"
        >
          <img
            :src="getImageUrl(image)"
            :alt="props.block.data.title || `Gallery image ${index + 1}`"
            class="aspect-[4/3] w-full object-cover transition duration-300 hover:scale-105"
            loading="lazy"
          >
        </figure>
      </div>
    </div>
  </section>
</template>
