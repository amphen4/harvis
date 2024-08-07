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
const apiState = ref({data: {}});
const fetchData = async (refresh = false) => {
  contentReady.value = false;
  const mlFlexApiConfiguration = localStorage.getItem('ML.FLEX.ApiConfiguration');
  if( mlFlexApiConfiguration && !refresh){
    apiState.value.data = JSON.parse(mlFlexApiConfiguration);
    contentReady.value = true;
  }else{
    const responseData = await fetchWrapper.get(`${import.meta.env.VITE_API_URL}/marketplaces/client_api`, {
      action: 'getEnviosFlexConfigs',
      marketplace: 'Mercadolibre'
    }).then((data) => {
      localStorage.setItem('ML.FLEX.ApiConfiguration', JSON.stringify(data));
      apiState.value.data = data;
    });
    contentReady.value = true;
  }
}
onMounted(async () => {
    fetchData()
})
</script>

<template>
  <BaseBreadcrumb :title="page.title" :breadcrumbs="breadcrumbs"></BaseBreadcrumb>
  <ContentSkeleton v-if="!contentReady"/>
  <Content @refresh="fetchData(true)" v-else :apiState="apiState.data" />
</template>
