<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useNews } from '../composables/useNews'

const props = defineProps<{
  news?: any[]
  years?: number[]
    featuredNews?: any[]
    categories?: string[]
    page?: Record<string, any>
    locale?: string
}>()

const {
  title,
  description,
  content,
  metaTitle,
  metaDescription,
  news,
  featuredNews,
  categories,
  searchQuery,
  activeCategory,
  showOnlyFeatured,
  onSearch,
  clearFilters,
  formatDate,
  getImageUrl,
  limit,
  pagination,
} = useNews({ page: props.page, news: props.news, featuredNews: props.featuredNews, categories: props.categories, locale: props.locale })

</script>

<template>
  <AppLayout :page="{ title: metaTitle, description: metaDescription, content: content }">
    <div class="news">
      <main class="content">
        <div class="container page-container mx-auto px-4 py-8">
          <!-- Header -->
          <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4 text-black">{{ title }}</h1>
            <p v-if="description" class="text-xl text-gray-600 max-w-2xl mx-auto">{{ description }}</p>
          </div>

          <div class="mb-8">
            <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
              <!-- Search -->
              <div class="w-full md:w-auto">
                <form @submit.prevent="onSearch" class="flex gap-2">
                  <input v-model="searchQuery" type="text" placeholder="Cerca nelle news..." class="flex-1 md:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                  <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Cerca</button>
                </form>
              </div>

              <!-- Filters -->
              <div class="flex gap-2 items-center">
                <span class="text-sm text-gray-600">Filtra per:</span>
                <button @click="clearFilters" class="px-3 py-1 text-sm rounded-full" :class="!activeCategory && !showOnlyFeatured ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'">Tutte</button>
                <button @click="() => { showOnlyFeatured = !showOnlyFeatured; onSearch(); }" class="px-3 py-1 text-sm rounded-full" :class="showOnlyFeatured ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'">⭐ In evidenza</button>
              </div>
            </div>
          </div>

          <!-- News Grid -->
          <div v-if="news.length > 0" class="news-gallery-grid" id="newsGrid">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
              <article v-for="(item, idx) in news" :key="item.id || idx" class="news-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300" :data-category="item.category || ''">
                <div v-if="item.featured" class="absolute top-4 left-4 z-10"><span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">⭐ In evidenza</span></div>
                <div v-if="item.excerpt_image" class="h-48 relative">
                  <img :src="getImageUrl(item.excerpt_image)" :alt="item.title" class="w-full h-full object-cover" loading="lazy" />
                </div>
                <div v-else class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 relative">
                  <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                  </div>
                </div>

                <div class="p-6 flex flex-col gap-4">
                  <div class="flex items-center justify-between text-sm text-gray-600">
                    <span v-if="item.category" class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">{{ item.category }}</span>
                    <time>{{ formatDate(item.published_at) }}</time>
                  </div>

                  <h6 class="text-lg font-semibold mb-3 hover:text-blue-600 transition-colors"><a :href="item.url || ('/news/' + item.slug)" class="text-black">{{ item.title }}</a></h6>

                  <p v-if="item.excerpt_or_description" class="text-gray-600 text-sm leading-relaxed">{{ limit(item.excerpt_or_description, 120) }}</p>

                  <div v-if="item.tags_array && item.tags_array.length" class="flex flex-wrap gap-1">
                    <span v-for="(t,i) in item.tags_array.slice(0,3)" :key="i" class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">{{ t }}</span>
                  </div>

                  <a :href="item.url || ('/news/' + item.slug)" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">Leggi di più →</a>
                </div>
              </article>
            </div>

            <!-- Pagination (Laravel paginator via Inertia) -->
            <div class="flex justify-center">
              <nav v-if="pagination && pagination.links" class="inline-flex rounded-md shadow">
                <a v-for="(link, i) in pagination.links" :key="i" :href="link.url || 'javascript:void(0)'" class="px-3 py-2 border text-sm" :class="link.active ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'" v-html="link.label"></a>
              </nav>
            </div>
          </div>

          <div v-else class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nessuna news disponibile</h3>
            <p class="mt-1 text-sm text-gray-500">Non ci sono ancora news pubblicate. Torna a trovarci presto!</p>
          </div>

          <!-- Featured News Section -->
          <section v-if="featuredNews && featuredNews.length > 0" class="featured-news mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">News in Evidenza</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <article v-for="(item, idx) in featuredNews" :key="item.id || idx" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div v-if="item.excerpt_image" class="aspect-video"><img :src="getImageUrl(item.excerpt_image)" :alt="item.title" class="w-full h-full object-cover" loading="lazy"/></div>
                <div class="p-4">
                  <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                    <span v-if="item.category" class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">{{ item.category }}</span>
                    <time>{{ formatDate(item.published_at) }}</time>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900 mb-2"><a :href="item.url || ('/news/' + item.slug)" class="hover:text-blue-600 transition-colors">{{ item.title }}</a></h3>
                  <p v-if="item.excerpt_or_description" class="text-gray-600 text-sm leading-relaxed">{{ limit(item.excerpt_or_description, 120) }}</p>
                </div>
              </article>
            </div>
          </section>

          <!-- Category filters -->
          <div v-if="categories && categories.length > 0" class="mb-8">
            <div class="flex flex-wrap gap-2 justify-center">
              <button @click.prevent="() => { activeCategory = null; clearFilters(); }" class="category-filter px-4 py-2 rounded-full text-sm font-medium" :class="!activeCategory ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'">Tutte le News</button>
              <button v-for="(c,i) in categories" :key="i" @click.prevent="() => { activeCategory = c; onSearch(); }" class="category-filter px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 text-sm font-medium transition-colors">{{ c.charAt(0).toUpperCase() + c.slice(1) }}</button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </AppLayout>
</template>
