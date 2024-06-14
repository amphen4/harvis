import './bootstrap';

import 'vuetify/styles'
import { createApp, h } from 'vue'
import '@mdi/font/css/materialdesignicons.css'
import { createVuetify } from 'vuetify'

import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'


const vuetify = createVuetify({
  components,
  directives,
})


import { createMemoryHistory, createRouter } from 'vue-router'

import Login from './views/Login.vue'

const routes = [
  { path: '/', component: Login, meta: { title: 'L0ogin' } },
]

const router = createRouter({
  history: createMemoryHistory(),
  routes,
})

import { createStore } from 'vuex'
const store = createStore({
    state () {
      return {
        count: 0
      }
    },
    mutations: {
        increment (state) {
          state.count++
        }
    }
})

import App from './App.vue';

createApp(App).use(vuetify).use(store).use(router).mount("#app");
