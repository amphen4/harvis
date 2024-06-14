import { ref } from 'vue';
import { createStore } from 'vuex';


export const ui = {

  namespaced: true,
  state:{
    // initialize state from local storage to enable user to stay logged in
    /* eslint-disable-next-line @typescript-eslint/ban-ts-comment */
    // @ts-ignore
    isLoading :false
  },

};
