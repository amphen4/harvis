import { createStore } from 'vuex'
import auth  from './auth'
import { customizer } from './customizer'
import { ui } from './ui'

//const debug = process.env.NODE_ENV !== 'production'

const store = createStore({
  modules: {
    auth,
    customizer,
    ui
  }
});

export default store;