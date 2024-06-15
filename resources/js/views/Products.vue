<script setup lang="ts">
import { ref, shallowRef, onMounted } from 'vue';

import BaseBreadcrumb from '@/components/shared/BaseBreadcrumb.vue';
import UiParentCard from '@/components/shared/UiParentCard.vue';
import { fetchWrapper } from '@/utils/helpers/fetch-wrapper';

const page = ref({ title: 'Productos' });
const breadcrumbs = shallowRef([
  {
    title: 'Productos',
    disabled: true,
    href: '#'
  }
]);
const productsTableHeaders = ref([
    {title: 'ID', value: 'products_id'}, //value o key?
    {title: 'Producto', value: 'name'},
    {title: 'Stock', value: 'stock'}
])
const productsTableData = ref([]);
const loadingTable = ref(false);
onMounted(async () => {
    const responseData = await fetchWrapper.get(`http://localhost:8000/api/products`);
    productsTableData.value = responseData.data;
})
</script>

<template>
  <BaseBreadcrumb :title="page.title" :breadcrumbs="breadcrumbs"></BaseBreadcrumb>
  <v-row>
    <v-col cols="12" md="12">
      <v-data-table :headers="productsTableHeaders" :loading="loadingTable" :items="productsTableData">

      </v-data-table>
    </v-col>
  </v-row>
</template>
