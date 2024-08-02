<script setup lang="ts">
import { RouterView } from 'vue-router';
import LoaderWrapper from './LoaderWrapper.vue';
import VerticalSidebarVue from './vertical-sidebar/VerticalSidebar.vue';
import VerticalHeaderVue from './vertical-header/VerticalHeader.vue';
import FooterPanel from './footer/FooterPanel.vue';
import { useStore } from 'vuex';
const store = useStore();
</script>

<template>
  <v-locale-provider>
    <v-app :class="[]">
      <VerticalSidebarVue />
      <VerticalHeaderVue />

      <v-main class="page-wrapper">
        <v-container>
          <div>
            <!-- Loader start -->
            <LoaderWrapper />
            <!-- Loader end -->
            <RouterView />
          </div>
        </v-container>
        <v-container class="pt-0">
          <div>
            <FooterPanel />
          </div>
        </v-container>
      </v-main>
      <v-snackbar :timeout="-1" color="warning" v-model="store.state.harvis.showSnackbar">
        <span v-if="store.state.harvis.snackbarMessages.length == 1">{{ store.state.harvis.snackbarMessages[0] }}</span>
        <v-virtual-scroll v-if="store.state.harvis.snackbarMessages.length > 1" :items="store.state.harvis.snackbarMessages" >
          <template v-slot:default="{ item }">
            <v-list-item density="compact">
              - {{ item }}
            </v-list-item>
          </template>
        </v-virtual-scroll>
        <template v-slot:actions>
          <v-btn @click="store.dispatch('harvis/CLOSE_SNACKBAR')" density="compact" icon="mdi-close"></v-btn>
        </template>
      </v-snackbar>
    </v-app>
  </v-locale-provider>
</template>
