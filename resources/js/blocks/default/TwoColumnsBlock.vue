<script setup lang="ts">

interface Props {
    block: {
        type: string;
        data: {
            title: string;
            content: string;
            subtitle: string;
            layout: string;
            image: string;
        };
    }
}

const props = defineProps<Props>();

</script>

<template>
    <section class="two-columns">
        <div class="custom-container mx-auto px-4 py-12">
            <div :class="`grid gap-8 md:gap-12 items-center ${props.block.data.layout === 'image-left' ? 'md:grid-cols-2' : 'md:grid-cols-2 md:grid-flow-dense'}`">
                <div :class="props.block.data.layout === 'image-left' ? '' : 'md:col-start-2'">
                    <img v-if="props.block.data.image" :src="`/storage/${props.block.data.image}`" :alt="props.block.data.title" class="w-full h-auto rounded-lg shadow-md" />
                </div>
                <div>
                    <h2 class="text-3xl font-bold mb-4">{{ props.block.data.title }}</h2>
                    <h3 v-if="props.block.data.subtitle" class="text-xl font-semibold mb-4">{{ props.block.data.subtitle }}</h3>
                    <p class="text-gray-700 leading-relaxed" v-html="props.block.data.content"></p>
                </div>
            </div>
        </div>
    </section>
</template>