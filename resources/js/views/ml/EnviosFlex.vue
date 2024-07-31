<script setup lang="ts">
import { ref, shallowRef, onMounted, readonly } from 'vue';

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
const apiStateReadonly = ref({hagoEnvios: false});
const apiStateReactive = ref({hagoEnvios: false});
onMounted(async () => {
    //const responseData = await fetchWrapper.get(`${import.meta.env.VITE_API_URL}/products`);
    //productsTableData.value = responseData.data;
    const data = {hagoEnvios: true};
    setTimeout(() => {
      apiStateReadonly.value = {hagoEnvios: readonly(ref(true))};
    }, 2000);
})
</script>

<template>
  <BaseBreadcrumb :title="page.title" :breadcrumbs="breadcrumbs"></BaseBreadcrumb>
  <v-row>
    <v-col cols="12" md="12">
      <v-card elevation="0">
        <v-card variant="outlined">
          <v-card-text>
            <v-switch label="Switch" v-model="apiStateReadonly.hagoEnvios"></v-switch>
          </v-card-text>
        </v-card>
      </v-card>
    </v-col>
  </v-row>
</template>
