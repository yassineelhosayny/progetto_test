<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

interface Props {
  page: {
    title: string
    description: string
    meta_description?: string
    meta_title?: string
    meta_keywords?: string
    content: string
    locale: string
  };
}

const props = defineProps<Props>();
</script>

<template>
  
  <Head>
    <title>{{ props.page.meta_title || props.page.title }}</title>
    <meta name="description" content="{{ props.page.description }}" />
  </Head>
  <div class="app-layout">

    <main>
      <slot />
    </main>

    <footer class="app-footer bg-gray-100 py-4 mt-8">
      <div class="container mx-auto px-4 text-center">
        <p>&copy; 2024 My Application. All rights reserved.</p>
      </div>
    </footer>
  </div>    
</template>