<script setup lang="ts">
import type { SearchResultItem } from '@/types/search'
import { computed } from 'vue'

const props = defineProps<{
  result: SearchResultItem
  locale: string
}>()

const typeLabel = computed(() => {
  switch (props.result.type) {
    case 'page':
      return 'Pagina'
    default:
      return props.result.type
  }
})
</script>

<template>
  <article class="flex flex-col gap-4 border-b border-gray-200 bg-white py-5">
    <div class="uppercase text-gray-500">
      {{ typeLabel }}
    </div>

    <h2 class="text-lg font-semibold leading-tight">
      <a
        :href="result.url"
        class="hover:underline"
      >
        {{ result.title }}
      </a>
    </h2>
    <p class="secondary" v-if="result.description" v-html="result.description"></p>
  </article>
</template>