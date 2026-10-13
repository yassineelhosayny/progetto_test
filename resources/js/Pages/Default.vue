<script setup lang="ts">
import { computed, defineAsyncComponent, type Component } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Block {
  type: string;
  data: Record<string, any>;
}

interface Page {
  id: number;
  title: string | Record<string, string>;
  slug: string;
  description?: string | Record<string, string>;
  content?: string | Record<string, string>;
  template: string;
  blocks?: Record<string, Block[]>; // Struttura: { "en": [...], "it": [...] }
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

// Gestione blocchi localizzati
const localizedBlocks = computed((): Block[] => {
  if (!props.page.blocks) return [];
  
  // Se blocks è un oggetto con chiavi di lingua { "en": [...], "it": [...] }
  if (typeof props.page.blocks === 'object' && !Array.isArray(props.page.blocks)) {
    const blocksForLocale = props.page.blocks[props.locale] || props.page.blocks['it'] || [];
    return Array.isArray(blocksForLocale) ? blocksForLocale : [];
  }
  
  // Fallback: se blocks è già un array (vecchia struttura)
  return Array.isArray(props.page.blocks) ? props.page.blocks : [];
});

/**
 * Carica dinamicamente il componente blocco basandosi sul tipo
 * Convenzione: type "hero" -> componente "HeroBlock.vue"
 * Path: @/components/blocks/homepage/HeroBlock.vue
 */
const getBlockComponent = (blockType: string): Component | null => {
  if (!blockType) return null;
  
  const componentName = blockType
    .split('_')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
    .join('');
  
  try {
    // Caricamento dinamico del componente
    return defineAsyncComponent(() =>
      import(`@/blocks/default/${componentName}Block.vue`)
        .catch(() => {
          console.warn(`Block component not found: ${componentName}Block.vue`);
          // return import('@/components/blocks/FallbackBlock.vue');
        })
    );
  } catch (error) {
    console.error(`Error loading block component: ${blockType}`, error);
    return null;
  }
};

</script>

<template>
  

  <AppLayout :page="{ title: metaTitle, description: metaDescription, content: content }">
    <div class="default">

    <!-- Content Section -->
    <main class="content">
      <div class="default-container">

        <!-- Blocchi dinamici localizzati -->
        <div v-if="localizedBlocks.length > 0" class="blocks space-y-8">
          <div
            v-for="(block, index) in localizedBlocks"
            :key="index"
            class="block">
            <component
              :is="getBlockComponent(block.type)"
              v-if="getBlockComponent(block.type)"
              :block="block"
            />
            <div v-else class="p-4 bg-gray-100 rounded">
              <p class="text-sm text-gray-600">Block type "{{ block.type }}" not found</p>
            </div>
           </div>
        </div>
      </div>
    </main>
    </div>
  </AppLayout>
</template>