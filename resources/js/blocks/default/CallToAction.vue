<script setup lang="ts">

interface Props {
    block: {
        type: string;
        data: {
            title: string;
            content: string;
            button_text: string;
            button_url: string;
            background_image: string;
        };
    }
}
const props = defineProps<Props>();

</script>

<template>
    <section class="cta">
        <div
            class="cta-background bg-cover bg-center py-20 px-6 text-center text-white"
            :style="`background-image: url(${props.block.data.background_image});`"
        >
            <h2 class="text-4xl font-bold mb-4">{{ props.block.data.title }}</h2>
            <p v-if="props.block.data.content" class="text-xl mb-6">{{ props.block.data.content }}</p>
            <a
                v-if="props.block.data.button_text && props.block.data.button_url"
                :href="props.block.data.button_url"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded"
            >
                {{ props.block.data.button_text }}
            </a>
        </div>
    </section>
</template>