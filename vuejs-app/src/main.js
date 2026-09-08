import { createApp } from 'vue'
import { createPinia } from 'pinia'
import './style.css'
import App from './App.vue'
import router from './router'

import "bootstrap/dist/js/bootstrap.bundle.min.js"
import "admin-lte/dist/js/adminlte.min.js"


const pinia = createPinia()
const app = createApp(App)

app.use(router)
app.use(pinia)

app.mount('#app')
