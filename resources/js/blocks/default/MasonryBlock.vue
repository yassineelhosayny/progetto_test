<script setup lang="ts">
import { computed } from 'vue';

interface MasonryItem {
    image?: string;
    alt?: string;
    caption?: string;
    link_url?: string;
}

interface Props {
    block: {
        type: string;
        data: {
            title?: string;
            description?: string;
            items?: MasonryItem[];
            columns?: string;
        };
    };
}

const props = defineProps<Props>();

const columnClass = computed(() => {
    const classes: Record<string, string> = {
        '2': 'columns-1 sm:columns-2',
        '3': 'columns-1 sm:columns-2 lg:columns-3',
        '4': 'columns-1 sm:columns-2 lg:columns-4',
    };

    return classes[props.block.data.columns || '3'] || classes['3'];
});

const imageUrl = (path?: string): string => {
    if (!path) {
        return '';
    }

    if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('/')) {
        return path;
    }

    return `/storage/${path}`;
};
</script>

<template>
    <section class="masonry py-12">
        <div class="container mx-auto px-4">
            <div v-if="props.block.data.title || props.block.data.description" class="mb-8 max-w-3xl">
                <h2 v-if="props.block.data.title" class="text-3xl font-bold text-gray-900">
                    {{ props.block.data.title }}
                </h2>
                <p v-if="props.block.data.description" class="mt-3 text-gray-600">
                    {{ props.block.data.description }}
                </p>
            </div>

            <div :class="[columnClass, 'gap-6']">
                <component
                    :is="item.link_url ? 'a' : 'div'"
                    v-for="(item, index) in props.block.data.items || []"
                    :key="index"
                    :href="item.link_url || undefined"
                    class="mb-6 block break-inside-avoid overflow-hidden rounded bg-white shadow-sm transition hover:shadow-md"
                >
                    <img
                        v-if="item.image"
                        :src="imageUrl(item.image)"
                        :alt="item.alt || item.caption || ''"
                        class="w-full object-cover"
                        loading="lazy"
                    >
                    <div v-if="item.caption" class="px-4 py-3 text-sm font-medium text-gray-800">
                        {{ item.caption }}
                    </div>
                </component>
            </div>
        </div>
    </section>
</template>
