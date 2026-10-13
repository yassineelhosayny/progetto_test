<script setup lang="ts">
interface Props {
    block: {
        type: string;
        data: {
            section_title?: string;
            section_description?: string;
            cards?: { image?: string; category?: string; title: string; description?: string; url?: string; button?: { title?: string; style?: string } }[];
            grid_columns?: string;
            card_style?: string;
        };
    }
}

const props = defineProps<Props>();
</script>

<template>
    <section class="cards-grid py-12">
        <div class="container mx-auto px-4">
            <h3 v-if="props.block.data.section_title" class="text-2xl font-bold mb-2">{{ props.block.data.section_title }}</h3>
            <p v-if="props.block.data.section_description" class="text-gray-600 mb-6">{{ props.block.data.section_description }}</p>

            <div :class="`grid gap-6 ${props.block.data.grid_columns === '4' ? 'grid-cols-4' : props.block.data.grid_columns === '3' ? 'grid-cols-3' : 'grid-cols-1 md:grid-cols-2'}`">
                <div v-for="(card, idx) in props.block.data.cards || []" :key="idx" class="card p-4 rounded shadow-sm">
                    <img v-if="card.image" :src="card.image" :alt="card.title" class="w-full h-40 object-cover rounded mb-3" />
                    <div class="text-xs text-gray-500 mb-1">{{ card.category }}</div>
                    <h4 class="font-semibold mb-2">{{ card.title }}</h4>
                    <p v-if="card.description" class="text-gray-600 mb-3">{{ card.description }}</p>
                    <a v-if="card.url && card.button?.title" :href="card.url" class="inline-block py-2 px-4 rounded bg-blue-600 text-white">{{ card.button.title }}</a>
                </div>
            </div>
        </div>
    </section>
</template>
