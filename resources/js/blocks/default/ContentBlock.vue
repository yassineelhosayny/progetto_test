<script setup lang="ts">
interface Props {
    block: {
        type: string;
        data: {
            pretitle?: string;
            title: string;
            description?: string;
            statistics?: { number: string; label: string; description?: string }[];
            buttons?: { title: string; url: string; style?: string }[];
            text_alignment?: string;
            background_color?: string;
        };
    }
}

const props = defineProps<Props>();
</script>

<template>
    <section :class="['content-block', props.block.data.background_color || '']">
        <div class="container mx-auto px-4 py-12" :class="`text-${props.block.data.text_alignment || 'center'}`">
            <p v-if="props.block.data.pretitle" class="text-sm uppercase text-gray-500 mb-2">{{ props.block.data.pretitle }}</p>
            <h2 class="text-3xl font-bold mb-4">{{ props.block.data.title }}</h2>
            <p v-if="props.block.data.description" class="text-gray-700 mb-6" v-html="props.block.data.description"></p>

            <div v-if="props.block.data.statistics && props.block.data.statistics.length" class="statistics grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div v-for="(stat, idx) in props.block.data.statistics" :key="idx" class="stat text-center">
                    <div class="text-2xl font-bold">{{ stat.number }}</div>
                    <div class="text-sm text-gray-600">{{ stat.label }}</div>
                    <p v-if="stat.description" class="text-xs text-gray-500">{{ stat.description }}</p>
                </div>
            </div>

            <div v-if="props.block.data.buttons && props.block.data.buttons.length" class="buttons flex justify-center gap-4">
                <a v-for="(b, i) in props.block.data.buttons" :key="i" :href="b.url" :class="['py-2 px-4 rounded', b.style === 'secondary' ? 'bg-gray-200' : 'bg-blue-600 text-white']">{{ b.title }}</a>
            </div>
        </div>
    </section>
</template>
