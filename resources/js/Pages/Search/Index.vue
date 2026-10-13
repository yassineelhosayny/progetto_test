<script setup lang="ts">
import SearchResultCard from '@/components/search/SearchResultCard.vue'
import Pagination from '@/components/search/Pagination.vue'
import type { SearchPageProps } from '@/types/search'

// Sostituisci questo import con il tuo layout reale, se lo usi
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps<SearchPageProps>()
</script>

<template>
  <!-- Se hai un layout globale, puoi wrappare qui -->
  
  <div class="search">
  <AppLayout :page="{ title: props.filters.q ? `Ricerca: ${props.filters.q}` : 'Ricerca', description: '', content: '' }">
      <main class="content pt-25 min-h-screen">
      <div class="default-container">
        <h4 class="bg-custom-red text-white py-5 px-3 uppercase">Cerca</h4>

      <div v-if="props.filters.q" class="custom-container mt-6">
        <p class="mb-4 text-sm text-gray-600">
          <template v-if="props.results.total > 0">
            {{ props.results.total }} risultati per
            <span class="font-medium">“{{ props.filters.q }}”</span>
          </template>
          <template v-else>
            Nessun risultato per
            <span class="font-medium">“{{ props.filters.q }}”</span>
          </template>
        </p>

        <div v-if="props.results.data.length" class="space-y-4">
          <SearchResultCard
            v-for="result in props.results.data"
            :key="`${result.type}-${result.url}`"
            :result="result"
            :locale="props.locale"
          />
        </div>

        <div v-else class="rounded-xl border border-gray-200 bg-white p-6 text-sm text-gray-600">
          Prova a cercare con termini diversi o più generici.
        </div>

        <Pagination
          v-if="props.results.last_page > 1"
          :links="props.results.links"
          class="mt-8"
        />
      </div>
    </div>
    </main>
  
  </AppLayout>
    </div>
 
</template>