<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { PaginationLink } from '@/types/search'

defineProps<{
  links: PaginationLink[]
}>()
</script>

<template>
  <nav aria-label="Paginazione">
    <ul class="flex flex-wrap gap-2">
      <li
        v-for="link in links"
        :key="`${link.label}-${link.url}`"
      >
        <span
          v-if="link.url === null"
          class="inline-flex min-w-10 items-center justify-center rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-400"
          v-html="link.label"
        />

        <Link
          v-else
          :href="link.url"
          class="inline-flex min-w-10 items-center justify-center rounded-lg border px-3 py-2 text-sm"
          :class="
            link.active
              ? 'border-black bg-black text-white'
              : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'
          "
          v-html="link.label"
        />
      </li>
    </ul>
  </nav>
</template>