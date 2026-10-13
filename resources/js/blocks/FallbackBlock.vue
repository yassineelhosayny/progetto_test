<script setup lang="ts">
interface Props {
  block: {
    type: string;
    data: Record<string, any>;
  };
}

defineProps<Props>();
</script>

<template>
  <div class="fallback-block p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
    <div class="flex items-start gap-3">
      <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
      </svg>
      <div>
        <p class="font-medium text-yellow-800">Block component not implemented</p>
        <p class="text-sm text-yellow-600 mt-1">Type: <code class="bg-yellow-100 px-2 py-0.5 rounded">{{ block.type }}</code></p>
        <details class="mt-2">
          <summary class="text-sm text-yellow-700 cursor-pointer hover:text-yellow-900">Show block data</summary>
          <pre class="mt-2 text-xs bg-yellow-100 p-3 rounded overflow-auto max-h-48">{{ JSON.stringify(block.data, null, 2) }}</pre>
        </details>
      </div>
    </div>
  </div>
</template>
