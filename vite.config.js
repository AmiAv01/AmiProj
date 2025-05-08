import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import viteCompression from 'vite-plugin-compression'; 

export default defineConfig({
  base: '/build/',

  plugins: [
    laravel({
      input: 'resources/js/app.js',
      buildDirectory: 'build',
      refresh: [
        'resources/views/**/*.blade.php',
        'public/images/**/*' 
      ],
    }),
    vue({
      template: {
        transformAssetUrls: {
          includeAbsolute: false,
        },
      },
    }),
    
    process.env.NODE_ENV === 'production' && viteCompression({
      algorithm: 'gzip',
      ext: '.gz',
      threshold: 10240 
    }),
    
    
  ].filter(Boolean),

  build: {
    outDir: 'public/build',
    emptyOutDir: true,     
    manifest: true,        
    sourcemap: false,     
    
    rollupOptions: {
      output: {
        assetFileNames: '[name]-[hash][extname]',
        chunkFileNames: '[name]-[hash].js',
        entryFileNames: '[name]-[hash].js',
        manualChunks(id) {
          if (id.includes('node_modules')) {
            return 'vendor';
          }
        }
      }
    }
  },


  optimizeDeps: {
    include: ['vue', 'vue-router', 'axios']
  }
});