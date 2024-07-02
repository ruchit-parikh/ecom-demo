import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'
import vuetify from './plugins/vuetify'
import '@mdi/font/css/materialdesignicons.css'
import eventBus from "@/plugins/eventBus"

const app = createApp(App)

app.use(vuetify)
app.use(createPinia())
app.use(router)
app.use({
  install: () => {
    app.config.globalProperties.$eventBus = eventBus
  }
})

app.mount('#app')
