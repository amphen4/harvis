import { createStore } from 'vuex';
import config from '@/config';


export const customizer = {
  namespaced: true,
  state: {
    Sidebar_drawer: config.Sidebar_drawer,
    mini_sidebar: config.mini_sidebar,
    actTheme: config.actTheme,
    fontTheme: config.fontTheme
  },

  getters: {},
  mutations:{
    setSidebarDrawer(state){
      state.Sidebar_drawer = !state.Sidebar_drawer
    },
    setMiniSidebar(state, value){
      state.mini_sidebar = value
    },
    setTheme(state, value){
      state.actTheme = value
    },
    setFont(state, value){
      state.fontTheme = value
    }
  },
  actions: {
    SET_SIDEBAR_DRAWER(context) {
      context.commit( 'setSidebarDrawer' )
    },
    SET_MINI_SIDEBAR(context, payload: boolean) {
      context.commit( 'setMiniSidebar', payload )
    },
    SET_THEME(context, payload: string) {
      context.commit( 'setTheme', payload )
    },
    SET_FONT(context, payload: string) {
      context.commit( 'setFont', payload )
    }
  }

};
