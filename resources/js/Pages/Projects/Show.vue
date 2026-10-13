<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'

interface Project {
  id: number
  title: string
  slug: string
  description?: string
  content?: string | null
  featured_image?: string | null
  gallery?: string[]
  published_at?: string | null
  url: string
  meta_title?: string
  meta_description?: string
}

const props = defineProps<{
  project: Project
  relatedProjects?: Project[]
  indexUrl: string
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
  <AppLayout :page="{ title: props.project.meta_title || props.project.title, description: props.project.meta_description || props.project.description }">
    <main class="content">
      <article class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <a :href="props.indexUrl" class="mb-8 inline-flex text-sm font-semibold text-blue-700 hover:text-blue-900">
          Torna ai progetti
        </a>

        <header class="max-w-4xl">
          <h1 class="text-4xl font-bold text-gray-950">{{ props.project.title }}</h1>
          <p v-if="props.project.description" class="mt-5 text-lg leading-8 text-gray-600">
            {{ props.project.description }}
          </p>
        </header>

        <div v-if="props.project.featured_image" class="mt-10 overflow-hidden rounded bg-gray-100">
          <img
            :src="imageUrl(props.project.featured_image)"
            :alt="props.project.title"
            class="h-auto w-full object-cover"
          >
        </div>

        <div
          v-if="props.project.content"
          class="prose prose-gray mt-10 max-w-none"
          v-html="props.project.content"
        />

        <section v-if="props.project.gallery && props.project.gallery.length" class="mt-12">
          <h2 class="mb-6 text-2xl font-semibold text-gray-950">Gallery</h2>
          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <img
              v-for="(image, index) in props.project.gallery"
              :key="index"
              :src="imageUrl(image)"
              :alt="`${props.project.title} ${index + 1}`"
              class="aspect-[4/3] w-full rounded object-cover"
              loading="lazy"
            >
          </div>
        </section>

        <section v-if="props.relatedProjects && props.relatedProjects.length" class="mt-16 border-t border-gray-200 pt-10">
          <h2 class="text-2xl font-semibold text-gray-950">Altri progetti</h2>
          <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
            <a
              v-for="relatedProject in props.relatedProjects"
              :key="relatedProject.id"
              :href="relatedProject.url"
              class="block rounded border border-gray-200 bg-white p-5 transition hover:border-blue-300 hover:shadow-sm"
            >
              <h3 class="font-semibold text-gray-950">{{ relatedProject.title }}</h3>
              <p v-if="relatedProject.description" class="mt-2 line-clamp-2 text-sm leading-6 text-gray-600">
                {{ relatedProject.description }}
              </p>
            </a>
          </div>
        </section>
      </article>
    </main>
  </AppLayout>
</template>
