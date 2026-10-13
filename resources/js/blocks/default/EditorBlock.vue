<script setup lang="ts">
interface Props {
    block: {
        type: string;
        data: {
            editor_repeater?: { editor_content: string }[];
        };
    }
}

const props = defineProps<Props>();
</script>

<template>
    <section class="editor-block py-8">
        <div class="container mx-auto px-4">
            <div v-if="props.block.data.editor_repeater && props.block.data.editor_repeater.length">
                <div v-for="(item, idx) in props.block.data.editor_repeater" :key="idx" class="editor-item mb-6">
                    <div v-html="item.editor_content" class="prose"></div>
                </div>
            </div>
            <div v-else>
                <p class="text-center text-gray-500">No content</p>
            </div>
        </div>
    </section>
</template>
