import { createStore } from 'vuex';
import config from '@/config';
import { v4 as uuidv4 } from 'uuid';

export const harvis = {
  namespaced: true,
  state: {
    notifications: [],
    loadingCounter: 0,
    snackbarMessages: [],
    showSnackbar: false,

  },
  getters: {},
  mutations:{
    incrementLoadingCounter(state){
      state.loadingCounter += 1;
    },
    decrementLoadingCounter(state){
      state.loadingCounter -= 1;
    },
    addError(state, value : Object){
      state.notifications.push({...value, id: uuidv4() })
    },
    removeErrorById(state, id){
      const errorElem = state.notifications.find(e => e.id == id);
      if( errorElem ){
        state.notifications.splice( state.notifications.indexOf(errorElem), 1);
      }
    },
    closeSnackbar(state){
      state.showSnackbar = false;
      state.snackbarMessages = [];
    },
    raiseSnackbar(state, messages : Array<String>){
      state.snackbarMessages = messages;
      state.showSnackbar = true;
    }
  },
  actions: {
    ADD_LOADING_COUNTER(context, amount) {
      if( amount === 1 ){
        context.commit( 'incrementLoadingCounter' )
      }else if( amount === -1){
        context.commit( 'decrementLoadingCounter' )
      }
    },
    ADD_NOTIFICATION(context, payload: Object) {
      context.commit( 'addError', payload )
    },
    REMOVE_NOTIFICATION(context, payload: string) {
      // TODO: Remover en backend
      context.commit( 'removeErrorById', payload )
    },
    CLOSE_SNACKBAR(context){
      context.commit('closeSnackbar');
    },
    RAISE_SNACKBAR(context, payload : Array<String>){
      context.commit('raiseSnackbar', payload)
    }
  }

};
