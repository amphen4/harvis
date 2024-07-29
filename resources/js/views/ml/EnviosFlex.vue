<script setup lang="ts">
import { ref, shallowRef, onMounted } from 'vue';

import BaseBreadcrumb from '@/components/shared/BaseBreadcrumb.vue';
import UiParentCard from '@/components/shared/UiParentCard.vue';
import { fetchWrapper } from '@/utils/helpers/fetch-wrapper';

const page = ref({ title: 'Mercadolibre - Envios Flex' });
const breadcrumbs = shallowRef([
  {
    title: 'Envios Flex',
    disabled: true,
    href: '#'
  }
]);
const productsTableHeaders = ref([
    //{title: 'ID', value: 'products_id'}, //value o key?
    {title: 'SKU', value: 'sku'},
    {title: 'Nombre Producto', value: 'name'},
    {title: 'Marketplaces', value: 'marketplaces'},
    {title: 'Precio', value: 'price'},
    {title: '', value: 'options'},
])
const productsTableData = ref([]);
const loadingTable = ref(false);
onMounted(async () => {
    console.log(import.meta.env.VITE_API_URL)
    const responseData = await fetchWrapper.get(`${import.meta.env.VITE_API_URL}/products`);
    productsTableData.value = responseData.data;
})
</script>

<template>
  <BaseBreadcrumb :title="page.title" :breadcrumbs="breadcrumbs"></BaseBreadcrumb>
  <v-row>
    <v-col cols="12" md="12">
          
    </v-col>
  </v-row>
</template>
