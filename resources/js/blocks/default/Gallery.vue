<script setup lang="ts">
interface Props {
    block: {
        type: string;
        data: {
            title?: string;
            description?: string;
            images?: { path: string; title?: string; alt: string; description?: string }[];
            grid_columns?: string;
            aspect_ratio?: string;
        };
    }
}

const props = defineProps<Props>();
</script>

<template>
    <section class="gallery py-8">
        <div class="container mx-auto px-4">
            <h3 v-if="props.block.data.title" class="text-2xl font-bold mb-2">{{ props.block.data.title }}</h3>
            <p v-if="props.block.data.description" class="text-gray-600 mb-4">{{ props.block.data.description }}</p>

            <div :class="`grid gap-4 ${props.block.data.grid_columns === '4' ? 'grid-cols-4' : props.block.data.grid_columns === '3' ? 'grid-cols-3' : 'grid-cols-2'}`">
                <div v-for="(img, idx) in props.block.data.images || []" :key="idx" class="gallery-item">
                    <img :src="img.path" :alt="img.alt" class="w-full h-auto object-cover rounded" />
                    <div v-if="img.title || img.description" class="mt-2">
                        <div class="font-semibold">{{ img.title }}</div>
                        <div class="text-sm text-gray-500">{{ img.description }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
