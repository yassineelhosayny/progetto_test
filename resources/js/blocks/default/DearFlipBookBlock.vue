<script setup lang="ts">
import DearflipBook from '@/components/tools/DearflipBook.vue';

interface Props {
    block: {
        type: string;
        data: {
            pdf_file: string;
        };
    };
}

const props = defineProps<Props>()

</script>

<template>
    <section class="flex justify-center custom-container">
        <DearflipBook :source="`/storage/${props.block.data.pdf_file}`" :options="{
            webgl: true,
            showSearchControl: true,
            showDownloadControl: true,
            showPrintControl: true,
            autoEnableOutline: true,
        }" />
    </section>
</template>