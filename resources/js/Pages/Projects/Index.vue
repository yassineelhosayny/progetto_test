<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'

interface Project {
  id: number
  title: string
  slug: string
  description?: string
  featured_image?: string | null
  published_at?: string | null
  url: string
}

interface PaginationLink {
  url: string | null
  label: string
  active: boolean
}

interface ProjectsPaginator {
  data: Project[]
  links?: PaginationLink[]
}

interface PageMeta {
  title: string
  description?: string
}

const props = defineProps<{
  projects: ProjectsPaginator
  page: PageMeta
  locale?: string
}>()

const imageUrl = (path?: string | null): string => {
  if (!path) {
    return ''
  }

  if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('/')) {
    return path
  }

  return `/storage/${path}`
}
</script>

<template>
  <AppLayout :page="{ title: page.title, description: page.description }">
    <main class="content">
      <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-10 max-w-3xl">
          <h1 class="text-4xl font-bold text-gray-950">{{ props.page.title }}</h1>
          <p v-if="props.page.description" class="mt-4 text-lg leading-8 text-gray-600">
            {{ props.page.description }}
          </p>
        </div>

        <div v-if="props.projects.data.length" class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-3">
          <article
            v-for="project in props.projects.data"
            :key="project.id"
            class="overflow-hidden rounded bg-white shadow-sm ring-1 ring-gray-200 transition hover:-translate-y-1 hover:shadow-md"
          >
            <a :href="project.url" class="block">
              <div class="aspect-[4/3] bg-gray-100">
                <img
                  v-if="project.featured_image"
                  :src="imageUrl(project.featured_image)"
                  :alt="project.title"
                  class="h-full w-full object-cover"
                  loading="lazy"
                >
              </div>

              <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-950">{{ project.title }}</h2>
                <p v-if="project.description" class="mt-3 line-clamp-3 text-sm leading-6 text-gray-600">
                  {{ project.description }}
                </p>
                <span class="mt-5 inline-flex text-sm font-semibold text-blue-700">
                  Apri progetto
                </span>
              </div>
            </a>
          </article>
        </div>

        <div v-else class="rounded border border-gray-200 bg-gray-50 px-6 py-10 text-center">
          <h2 class="text-lg font-semibold text-gray-950">Nessun progetto disponibile</h2>
          <p class="mt-2 text-sm text-gray-600">I progetti pubblicati appariranno qui.</p>
        </div>

        <nav v-if="props.projects.links && props.projects.links.length > 3" class="mt-12 flex flex-wrap justify-center gap-2">
          <a
            v-for="(link, index) in props.projects.links"
            :key="index"
            :href="link.url || '#'"
            class="rounded border px-3 py-2 text-sm"
            :class="[
              link.active ? 'border-blue-700 bg-blue-700 text-white' : 'border-gray-200 bg-white text-gray-700 hover:bg-gray-50',
              !link.url ? 'pointer-events-none opacity-50' : '',
            ]"
            v-html="link.label"
          />
        </nav>
      </section>
    </main>
  </AppLayout>
</template>
