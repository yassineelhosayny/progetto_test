<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';

interface Page {
  id: number;
  title: string | Record<string, string>;
  slug: string;
  description?: string | Record<string, string>;
  content?: string | Record<string, string>;
  template: string;
  blocks?: Array<{ type: string; data: Record<string, any> }>;
  meta_title?: string | Record<string, string>;
  meta_description?: string | Record<string, string>;
  is_homepage: boolean;
  status: string;
}

interface Props {
  page: Page;
  locale: string;
  auth: {
    user: any | null;
  };
}

const props = defineProps<Props>();

// Gestione campi multilingua
const getLocalizedValue = (value: string | Record<string, string> | undefined): string => {
  if (!value) return '';
  if (typeof value === 'string') return value;
  return value[props.locale] || value['it'] || Object.values(value)[0] || '';
};

const title = computed(() => getLocalizedValue(props.page.title));
const description = computed(() => getLocalizedValue(props.page.description));
const content = computed(() => getLocalizedValue(props.page.content));
const metaTitle = computed(() => getLocalizedValue(props.page.meta_title) || title.value);
const metaDescription = computed(() => getLocalizedValue(props.page.meta_description) || description.value);
</script>

<template>
  <Head>
    <title>{{ metaTitle }}</title>
    <meta name="description" :content="metaDescription" />
  </Head>

  <div class="homepage">
    <!-- Header/Hero Section -->
    <header class="hero">
      <div class="container mx-auto px-4 py-16">
        <h1 class="text-4xl md:text-6xl font-bold mb-6">
          {{ title }}
        </h1>
        <p v-if="description" class="text-xl md:text-2xl mb-8">
          {{ description }}
        </p>
      </div>
    </header>

    <!-- Content Section -->
    <main class="content">
      <div class="container mx-auto px-4 py-12">
        <div v-if="content" class="prose max-w-none" v-html="content"></div>

        <!-- Blocchi dinamici (se presenti) -->
        <div v-if="page.blocks && page.blocks.length > 0" class="blocks mt-12">
          <div
            v-for="(block, index) in page.blocks"
            :key="index"
            class="block mb-8"
          >
            <!-- Qui andranno i componenti per ogni tipo di blocco -->
            <!-- Per ora mostriamo solo il tipo -->
            <div class="p-4 bg-gray-100 rounded">
              <p class="text-sm text-gray-600">Block type: {{ block.type }}</p>

            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="footer bg-gray-900 text-white py-8">
      <div class="container mx-auto px-4 text-center">
        <p>&copy; {{ new Date().getFullYear() }} - Filament Base</p>
      </div>
    </footer>
  </div>
</template>
