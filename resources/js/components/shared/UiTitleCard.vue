<script setup lang="ts">
const props = defineProps({
  title: String,
  className: String,
  subtitle: String
});
</script>

<template>
  <v-card class="title-card" variant="text" rounded="md">
    <v-card-item class="pb-2 px-0 pt-0">
      <v-card-title class="text-h5 pb-2">{{ props.title }}</v-card-title>
      <v-card-subtitle class="text-subtitle-1 pb-2" v-if="props.subtitle">{{props.subtitle}}</v-card-subtitle>
    </v-card-item>
    <v-card-text variant="outlined" :class="`${props.className}`">
      <slot />
    </v-card-text>
  </v-card>
</template>
