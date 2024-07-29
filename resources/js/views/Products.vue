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
    const responseData = await fetchWrapper.get(`${import.meta.env.VITE_API_URL}/products`);
    productsTableData.value = responseData.data;
})
</script>

<template>
  <BaseBreadcrumb :title="page.title" :breadcrumbs="breadcrumbs"></BaseBreadcrumb>
  <v-row>
    <v-col cols="12" md="12">
      <v-data-table :headers="productsTableHeaders" :loading="loadingTable" :items="productsTableData">
        <template v-slot:item.options="{ }">
          <v-menu>
            <template v-slot:activator="{ props }">
              <v-btn
                color="primary"
                variant="plain"
                append-icon="mdi-dots-vertical"
                v-bind="props"
              >
                Opciones
              </v-btn>
            </template>

            <v-list density="compact">
              <v-list-item :link="true">
                <v-list-item-title>Ver detalles</v-list-item-title>
                <template v-slot:append>
                  <v-icon>mdi-eye</v-icon>
                </template>
              </v-list-item>
              <v-list-item :link="true">
                <v-list-item-title>Eliminar</v-list-item-title>
                <template v-slot:append>
                  <v-icon>mdi-delete</v-icon>
                </template>
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
        <template v-slot:item.marketplaces="{ item }">
          <div style="display: flex; justify-content: start;">
            <v-avatar size="16px" v-for="(logo_path,i) in item.logos_marketplaces.split(',')" :key="i+logo_path">
              <v-img
                :src="logo_path"
              ></v-img>
            </v-avatar>
            
          </div>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>
