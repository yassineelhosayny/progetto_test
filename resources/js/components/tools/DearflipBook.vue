<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { loadDearflip } from '@/lib/loadDearflip'

type DearflipOptions = {
    source: string | string[]
    webgl?: boolean
    webglShadow?: boolean
    soundEnable?: boolean
    height?: number | string
    autoEnableOutline?: boolean
    autoEnableThumbnail?: boolean
    overwritePDFOutline?: boolean
    showDownloadControl?: boolean
    showSearchControl?: boolean
    showPrintControl?: boolean
    duration?: number
    direction?: number
    pageMode?: number
    [key: string]: unknown
}

const props = withDefaults(defineProps<{
    id?: string
    source: string
    height?: number | string
    options?: Partial<DearflipOptions>
    className?: string
}>(), {
    id: undefined,
    height: 800,
    options: () => ({}),
    className: '',
})

const localId = `df-book-${Math.random().toString(36).slice(2, 10)}`
const elementId = computed(() => props.id ?? localId)
const ready = ref(false)

onMounted(async () => {
    await loadDearflip()

    // utile se DearFlip non trova correttamente la base path
    window.dFlipLocation = '/vendor/dflip/'

    const optionName = `option_${elementId.value}`

    window[optionName] = {
        source: props.source,
        height: props.height,
        ...props.options,
    }

    // DearFlip inizializza automaticamente dagli elementi nel DOM.
    // Se nella tua versione non parte da solo, sotto trovi il fallback.
    ready.value = true

    // fallback opzionale:
    // const jq = window.jQuery
    // if (jq && jq(`#${elementId.value}`).length && typeof jq.fn?.flipBook === 'function') {
    //   jq(`#${elementId.value}`).flipBook(props.source, window[optionName])
    // }
})
</script>

<template>
    <div :id="elementId" class="_df_book" :class="className" :source="source" style="width: 100%;" v-show="ready" />
</template>