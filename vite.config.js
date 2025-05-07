import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import viteCompression from 'vite-plugin-compression'; // Для сжатия ассетов

export default defineConfig({
  // Базовый URL (актуально если проект в подпапке)
  base: process.env.NODE_ENV === 'production' ? '/' : '',

  plugins: [
    laravel({
      input: 'resources/js/app.js',
      refresh: [
        'resources/views/**/*.blade.php',
        'public/images/**/*' // Следим за изменениями изображений
      ],
    }),
    vue({
      template: {
        transformAssetUrls: {
          // Отключаем трансформацию абсолютных путей
          includeAbsolute: false,
        },
      },
    }),
    
    
  ].filter(Boolean),

  build: {
    outDir: 'public/build', // Папка для сборки
    emptyOutDir: true,     // Очищать перед сборкой
    manifest: true,        // Генерировать manifest.json
    sourcemap: false,      // Отключаем sourcemaps для production
    
    rollupOptions: {
      output: {
        // Форматы имен файлов
        assetFileNames: 'assets/[name]-[hash][extname]',
        chunkFileNames: 'assets/[name]-[hash].js',
        entryFileNames: 'assets/[name]-[hash].js',
        
        // Разделение vendor-кода
        manualChunks(id) {
          if (id.includes('node_modules')) {
            return 'vendor';
          }
        }
      }
    }
  },

  resolve: {
    alias: {
      '@': '/resources/js',
      '~images': '/public/images' // Алиас для публичных изображений
    }
  },

  // Оптимизация для production
  optimizeDeps: {
    include: ['vue', 'vue-router', 'axios'] // Предкомпиляция зависимостей
  }
});