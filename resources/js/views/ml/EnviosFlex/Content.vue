<template>
  <v-row>
    <v-col cols="12" md="12">
      <v-row>
        <v-col class="pl-0" cols="12" md="12">
          <UiTitleCard title="Tiempos de entrega" subtitle="Establece los días y horarios en los que ofreces envíos, y el máximo de envíos que podrás entregar." class-name="mt-2 px-0 pb-0 rounded-md">
          <v-card elevation="0">
            <v-card-text>
              <v-switch color="success" :label="apiStateReactive.hagoEnvios ? 'Hago Envíos' : 'No hago envíos'" v-model="apiStateReactive.hagoEnvios"></v-switch>
            </v-card-text>
          </v-card>
          </UiTitleCard>
        </v-col>
      </v-row>
      <v-row>
        <UiTitleCard title="" class-name="px-0 pb-0 rounded-md mt-2">
          <v-table class="bordered-table" hover density="comfortable">
            <thead class="bg-containerBg">
              <tr>
                <th class="text-left text-caption font-weight-bold text-uppercase">Días de entrega</th>
                <th class="text-left text-caption font-weight-bold text-uppercase">Horarios de entrega</th>
                <th class="text-left text-caption font-weight-bold text-uppercase">Máximo de envíos</th>
                <th class="text-left text-caption font-weight-bold text-uppercase">Horario de corte</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Lunes a viernes</td>
                <td>
                  <v-row>
                    <v-col cols="12" md="6"><v-select variant="outlined" label="Desde"></v-select></v-col>
                    <v-col cols="12" md="6"><v-select variant="outlined" label="Hasta"></v-select></v-col>
                  </v-row>
                </td>
                <td>
                  <v-row>
                    <v-col cols="12" md="12"><v-text-field variant="outlined" label="Máximo de envíos"></v-text-field></v-col>
                  </v-row>
                </td>
                <td>
                  <v-row>
                    <v-col cols="12" md="12"><v-select variant="outlined" label="Horario de corte"></v-select></v-col>
                  </v-row>
                </td>
              </tr>
              <tr>
                <td class="pl-2"><v-checkbox label="Sábados"></v-checkbox></td>
                <td>
                  <v-row>
                    <v-col cols="12" md="6"><v-select variant="outlined" label="Desde"></v-select></v-col>
                    <v-col cols="12" md="6"><v-select variant="outlined" label="Hasta"></v-select></v-col>
                  </v-row>
                </td>
                <td>
                  <v-row>
                    <v-col cols="12" md="12"><v-text-field variant="outlined" label="Máximo de envíos"></v-text-field></v-col>
                  </v-row>
                </td>
                <td>
                  <v-row>
                    <v-col cols="12" md="12"><v-select variant="outlined" label="Horario de corte"></v-select></v-col>
                  </v-row>
                </td>
              </tr>
              <tr>
                <td class="pl-2"><v-checkbox label="Domingos"></v-checkbox></td>
                <td>
                  <v-row>
                    <v-col cols="12" md="6"><v-select variant="outlined" label="Desde"></v-select></v-col>
                    <v-col cols="12" md="6"><v-select variant="outlined" label="Hasta"></v-select></v-col>
                  </v-row>
                </td>
                <td>
                  <v-row>
                    <v-col cols="12" md="12"><v-text-field variant="outlined" label="Máximo de envíos"></v-text-field></v-col>
                  </v-row>
                </td>
                <td>
                  <v-row>
                    <v-col cols="12" md="12"><v-select variant="outlined" label="Horario de corte"></v-select></v-col>
                  </v-row>
                </td>
              </tr>
            </tbody>
          </v-table>
        </UiTitleCard>
      </v-row>
      <v-row class="pt-8">
        <UiTitleCard title="Zonas de cobertura" subtitle="Elige a qué zonas quieres hacer tus envíos y ajusta tu operación para evitar retrasos. El precio que paga el comprador varía según la distancia entre tu ubicación y el domicilio de entrega." class-name="px-0 pb-0 rounded-md mt-2">
          <v-list class="py-0">
            <v-list-item variant="tonal">
              <v-list-item-title><v-checkbox label="Seleccionar todas las zonas"></v-checkbox></v-list-item-title>
              <template v-slot:append>
                <span class="text-subtitle-1">1 seleccionadas</span>
              </template>
            </v-list-item>
            <v-list-item>
              <v-list-item-title><v-checkbox label="Santiago"></v-checkbox></v-list-item-title>
              <template v-slot:append>
                <span class="text-subtitle-1">$8990</span>
              </template>
            </v-list-item>
          </v-list>
        </UiTitleCard>
      </v-row>
    </v-col>
  </v-row>
</template>

<script setup>
import { ref, defineProps } from 'vue';
import UiTitleCard from '@/components/shared/UiTitleCard.vue';

// Obtener las props
const props = defineProps({apiState: Object});

// Variable reactiva
const apiStateReactive = ref(props.apiState);


</script>
