<script setup lang="ts">
import { shallowRef, ref, computed } from 'vue';
//import { useCustomizerStore } from '../../../stores/customizer';
import sidebarItems from './sidebarItem';

import NavGroup from './NavGroup/NavGroup.vue';
import NavItem from './NavItem/NavItem.vue';
import NavCollapse from './NavCollapse/NavCollapse.vue';
import ExtraBox from './extrabox/ExtraBox.vue';
import Logo from '../logo/LogoDark.vue';

//const customizer = useCustomizerStore;
const sidebarMenu = shallowRef(sidebarItems);

import { useStore } from 'vuex'
const store = useStore();


const Sidebar_drawer = computed(() => store.state.customizer.Sidebar_drawer);
const mini_sidebar = computed(() => store.state.customizer.mini_sidebar);
const prueba = (e) => {
  store.dispatch('customizer/SET_SIDEBAR_DRAWER');
}
</script>

<template>
  <v-navigation-drawer
    left
    :model-value="Sidebar_drawer"
    elevation="0"
    rail-width="60"
    mobile-breakpoint="lg"
    app
    class="leftSidebar"
    :rail="mini_sidebar"
    expand-on-hover
    @update:modelValue="prueba"
  >
    <div class="pa-5">
      <Logo />
    </div>
    <!-- ---------------------------------------------- -->
    <!---Navigation -->
    <!-- ---------------------------------------------- -->
    <perfect-scrollbar class="scrollnavbar">
      <v-list aria-busy="true" aria-label="menu list">
        <!---Menu Loop -->
        <template v-for="(item, i) in sidebarMenu" :key="i">
          <!---Item Sub Header -->
          <NavGroup :item="item" v-if="item.header" :key="item.title" />
          <!---Item Divider -->
          <v-divider class="my-3" v-else-if="item.divider" />
          <!---If Has Child -->
          <NavCollapse class="leftPadding" :item="item" :level="0" v-else-if="item.children" />
          <!---Single Item-->
          <NavItem :item="item" v-else />
          <!---End Single Item-->
        </template>
      </v-list>
      <div class="pa-4">
        <ExtraBox />
      </div>
    </perfect-scrollbar>
  </v-navigation-drawer>
</template>
