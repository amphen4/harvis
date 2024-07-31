<script setup lang="ts">
import { ref, shallowRef, onMounted, readonly } from 'vue';

import BaseBreadcrumb from '@/components/shared/BaseBreadcrumb.vue';
import UiParentCard from '@/components/shared/UiParentCard.vue';
import { fetchWrapper } from '@/utils/helpers/fetch-wrapper';

import ContentSkeleton from './ContentSkeleton.vue';
import Content from './Content.vue';

const page = ref({ title: 'Mercadolibre - Envios Flex' });
const breadcrumbs = shallowRef([
  {
    title: 'Envios Flex',
    disabled: true,
    href: '#'
  }
]);

const productsTableData = ref([]);
const contentReady = ref(false);
const apiState = ref({hagoEnvios: false});
onMounted(async () => {
    //const responseData = await fetchWrapper.get(`${import.meta.env.VITE_API_URL}/products`);
    //productsTableData.value = responseData.data;
    setTimeout(() => {
      console.log('AAA');
      apiState.value.hagoEnvios = true;
      contentReady.value = true;
      console.log('BBBB');
    }, 2000);
})
</script>

<template>
  <BaseBreadcrumb :title="page.title" :breadcrumbs="breadcrumbs"></BaseBreadcrumb>
  <ContentSkeleton v-if="!contentReady"/>
  <Content v-else :apiState="apiState" />
</template>
