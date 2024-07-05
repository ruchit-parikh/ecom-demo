import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import dotenv from 'dotenv';
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import vueDevTools from 'vite-plugin-vue-devtools'
import vuetify from 'vite-plugin-vuetify'

dotenv.config();

export default defineConfig({
  define: {
    'import.meta.env.VUE_APP_API_URL': JSON.stringify(process.env.VUE_APP_API_URL),
  },
  server: {
    host: '0.0.0.0'
  },
  plugins: [
    vue(),
    vueJsx(),
    vueDevTools(),
    vuetify(),
  ],
  test: {
    globals: true,
    environment: 'jsdom',
    server: {
      deps: {
        inline: ['vuetify'],
      },
    },
  },
  optimizeDeps: {
    include: [
      'vue',
      'vuetify'
    ],
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  }
})
