import { createStore } from 'vuex'
import auth  from './auth'
import { customizer } from './customizer'
import { ui } from './ui'
import { harvis } from './harvis';

//const debug = process.env.NODE_ENV !== 'production'

const store = createStore({
  modules: {
    auth,
    customizer,
    ui,
    harvis
  }
});

export default store;