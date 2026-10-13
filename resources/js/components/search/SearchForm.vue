<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const props = defineProps<{
  initialQuery: string
}>()

const query = ref(props.initialQuery ?? '')

watch(
  () => props.initialQuery,
  (value) => {
    query.value = value ?? ''
  }
)

function submit() {
  router.get(
    '/search',
    { q: query.value },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  )
}
</script>

<template>
  <form @submit.prevent="submit" class="rounded-xl border border-gray-200 bg-white p-4">
    <div class="flex flex-col gap-3 sm:flex-row">
      <input
        v-model="query"
        type="text"
        name="q"
        placeholder="Cerca pagine, news, eventi..."
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-gray-500"
      />

      <div class="flex gap-2">
        <button
          type="submit"
          class="rounded-lg bg-black px-4 py-2.5 text-sm font-medium text-white"
        >
          Cerca
        </button>

        <button
          v-if="query"
          type="button"
          class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
          @click="
            query = '';
            submit();
          "
        >
          Reset
        </button>
      </div>
    </div>
  </form>
</template>