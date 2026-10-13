
<script setup lang="ts">
interface GalleryImage {
  path: string;
  title?: string | null;
  alt?: string | null;
  description?: string | null;
}

interface Props {
  block: {
    type: string;
    data: {
      title?: string | null;
      description?: string | null;
      images: GalleryImage[];
      aspect_ratio?: string; // 'square', '16/9', '4/3', etc.
      grid_columns?: string; // '1', '2', '3', '4'
    };
  };
}

const props = defineProps<Props>();
</script>

<template>
  <div class="gallery-block"></div>
</template>

