import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/js/app.js',
        'resources/js/inertia.ts', // Nuovo entry per Inertia/Vue
        'resources/css/master.css',
        'resources/scss/app.scss',  // Aggiunto SASS
        'resources/css/filament/admin/theme.css', // Aggiunto Custom Theme
      ],
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  css: {
    postcss: './postcss.config.js',
  },
  server: {
    proxy: {
      '/storage': {
        target: 'http://127.0.0.1:8000',
        changeOrigin: true,
        secure: false,
      },
    },
    host: '0.0.0.0',
    port: 5175,
    strictPort: true,
    cors: true, // abilita CORS esplicitamente
    hmr: {
      host: 'localhost',
      port: 5176,
      timeout: 60000, // timeout più generoso
    },
    watch: {
      usePolling: true,
      interval: 100,
      ignored: ['**/node_modules/**', '**/vendor/**'], // ignora cartelle pesanti
    },
    // Ottimizzazioni per container
    fs: {
      strict: false,
    },
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './resources/js'),
    },
  },
  // Ottimizzazioni per performance
  optimizeDeps: {
    include: ['laravel-vite-plugin', 'vue', '@inertiajs/vue3'],
    exclude: ['lightningcss', '@tailwindcss/vite'],
  },
});