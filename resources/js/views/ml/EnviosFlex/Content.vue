<template>
  <v-row>
    <v-col cols="12" md="12">
      <v-row>
        <v-col cols="12" md="12">
          <v-sheet height="auto">
            <v-btn class="float-md-right mx-2" variant="outlined" color="primary" small>
              <v-icon icon="mdi-flash"></v-icon> &nbsp;
              Aplicar configuración
            </v-btn>
            <v-btn @click="dialogs.scheduling = true" class="float-md-right mx-2" variant="outlined" color="primary" small>
              <v-icon icon="mdi-calendar-clock"></v-icon> &nbsp;
              Programar configuración
            </v-btn>
          </v-sheet>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <UiTitleCard title="Tiempos de entrega" subtitle="Establece los días y horarios en los que ofreces envíos, y el máximo de envíos que podrás entregar." class-name="mt-2 px-0 pb-0 rounded-md">
          <v-card elevation="0">
            <v-card-text>
              <v-switch hide-details="auto" color="success" :label="apiStateReactive.hagoEnvios ? 'Si hago envíos en el día' : 'No hago envíos en el día'" v-model="apiStateReactive.hagoEnvios"></v-switch>
            </v-card-text>
          </v-card>
          </UiTitleCard>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
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
                      <v-col cols="12" md="6"><v-select :items="rangehours" class="my-2" hide-details="auto" variant="outlined" label="Desde"></v-select></v-col>
                      <v-col cols="12" md="6"><v-select :items="rangehours" class="my-2" hide-details="auto" variant="outlined" label="Hasta"></v-select></v-col>
                    </v-row>
                  </td>
                  <td>
                    <v-row>
                      <v-col cols="12" md="12"><v-text-field type="number" min="1" hide-details="auto" variant="outlined" label="Máximo de envíos"></v-text-field></v-col>
                    </v-row>
                  </td>
                  <td>
                    <v-row>
                      <v-col cols="12" md="12"><v-select :items="rangehours" hide-details="auto" variant="outlined" label="Horario de corte"></v-select></v-col>
                    </v-row>
                  </td>
                </tr>
                <tr>
                  <td class="pl-2"><v-checkbox v-model="saturdayEnabled" hide-details="auto" label="Sábados"></v-checkbox></td>
                  <td>
                    <v-row>
                      <v-col cols="12" md="6"><v-select :disabled="!saturdayEnabled" :items="rangehours" class="my-2" hide-details="auto" variant="outlined" label="Desde"></v-select></v-col>
                      <v-col cols="12" md="6"><v-select :disabled="!saturdayEnabled" :items="rangehours" class="my-2" hide-details="auto" variant="outlined" label="Hasta"></v-select></v-col>
                    </v-row>
                  </td>
                  <td>
                    <v-row>
                      <v-col cols="12" md="12"><v-text-field :disabled="!saturdayEnabled" type="number" min="1" hide-details="auto" variant="outlined" label="Máximo de envíos"></v-text-field></v-col>
                    </v-row>
                  </td>
                  <td>
                    <v-row>
                      <v-col cols="12" md="12"><v-select :disabled="!saturdayEnabled" :items="rangehours" hide-details="auto" variant="outlined" label="Horario de corte"></v-select></v-col>
                    </v-row>
                  </td>
                </tr>
                <tr>
                  <td class="pl-2"><v-checkbox v-model="sundayEnabled" hide-details="auto" label="Domingos"></v-checkbox></td>
                  <td>
                    <v-row>
                      <v-col cols="12" md="6"><v-select :disabled="!sundayEnabled" :items="rangehours" class="my-2" hide-details="auto" variant="outlined" label="Desde"></v-select></v-col>
                      <v-col cols="12" md="6"><v-select :disabled="!sundayEnabled" :items="rangehours" class="my-2" hide-details="auto" variant="outlined" label="Hasta"></v-select></v-col>
                    </v-row>
                  </td>
                  <td>
                    <v-row>
                      <v-col cols="12" md="12"><v-text-field :disabled="!sundayEnabled" type="number" min="1" hide-details="auto" variant="outlined" label="Máximo de envíos"></v-text-field></v-col>
                    </v-row>
                  </td>
                  <td>
                    <v-row>
                      <v-col cols="12" md="12"><v-select :disabled="!sundayEnabled" :items="rangehours" hide-details="auto" variant="outlined" label="Horario de corte"></v-select></v-col>
                    </v-row>
                  </td>
                </tr>
              </tbody>
            </v-table>
          </UiTitleCard>
        </v-col>
      </v-row>
      <v-row class="pt-8">
        <v-col cols="12">
          <UiTitleCard title="Zonas de cobertura" subtitle="Elige a qué zonas quieres hacer tus envíos y ajusta tu operación para evitar retrasos. El precio que paga el comprador varía según la distancia entre tu ubicación y el domicilio de entrega." class-name="px-0 pb-0 rounded-md mt-2">
            <v-list class="py-0">
              <v-list-item class="pl-2" variant="tonal">
                <v-list-item-title><v-checkbox v-model="allZones" hide-details="auto" label="Seleccionar todas las zonas"></v-checkbox></v-list-item-title>
                <template v-slot:append>
                  <span class="text-subtitle-1">{{ zones.filter(e => e.enabled).length }} seleccionadas</span>
                </template>
              </v-list-item>
              <v-list-item v-for="zone in zones" :key="zone.name" class="pl-2">
                <v-list-item-title><v-checkbox v-model="zone.enabled" hide-details="auto" :label="zone.name"></v-checkbox></v-list-item-title>
                <template v-slot:append>
                  <span class="text-subtitle-1">${{zone.cost}}</span>
                </template>
              </v-list-item>
            </v-list>
          </UiTitleCard>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
  <v-dialog v-model="dialogs.scheduling" max-width="500">
    <template v-slot:default>
      <v-card rounded="lg">
        <v-card-title class="d-flex justify-space-between align-center">
          <div class="text-h5 text-medium-emphasis ps-2">
            Programar configuración
          </div>

          <v-btn
            icon="mdi-close"
            variant="text"
            @click="dialogs.scheduling = false"
          ></v-btn>
        </v-card-title>
        <v-divider class="mb-1"></v-divider>
        <v-card-text>
          <v-row>
            <v-col cols="12">
              <v-sheet height="auto" class="text-subtitle-1 my-4">
                Selecciona el modo:
              </v-sheet>
              <v-sheet>
                <v-select color="primary" hide-details="auto" variant="outlined" label="Tipo de frecuencia" :items="['Semanal','Diaria']" v-model="scheduling.frequencyType"></v-select>
              </v-sheet>
            </v-col>
          </v-row>
          <v-row v-if="scheduling.frequencyType == 'Semanal'">
            <v-col cols="12">
              <v-sheet height="auto" class="text-subtitle-1 my-4">
                Selecciona el(los) dia(s) a programar:
              </v-sheet>
              <v-sheet style="display:flex; justify-content: space-between;">
                <v-btn rounded="xl" variant="outlined" @click="switchProgrammedDay(1)" :color="scheduling.weekdays.includes(1) ? 'primary' : 'default'">L</v-btn>
                <v-btn rounded="xl" variant="outlined" @click="switchProgrammedDay(2)" :color="scheduling.weekdays.includes(2) ? 'primary' : 'default'">M</v-btn>
                <v-btn rounded="xl" variant="outlined" @click="switchProgrammedDay(3)" :color="scheduling.weekdays.includes(3) ? 'primary' : 'default'">X</v-btn>
                <v-btn rounded="xl" variant="outlined" @click="switchProgrammedDay(4)" :color="scheduling.weekdays.includes(4) ? 'primary' : 'default'">J</v-btn>
                <v-btn rounded="xl" variant="outlined" @click="switchProgrammedDay(5)" :color="scheduling.weekdays.includes(5) ? 'primary' : 'default'">V</v-btn>
                <v-btn rounded="xl" variant="outlined" @click="switchProgrammedDay(6)" :color="scheduling.weekdays.includes(6) ? 'primary' : 'default'">S</v-btn>
                <v-btn rounded="xl" variant="outlined" @click="switchProgrammedDay(7)" :color="scheduling.weekdays.includes(7) ? 'primary' : 'default'">D</v-btn>
              </v-sheet>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <v-sheet height="auto" class="text-subtitle-1 my-4">
                Selecciona el(los) horario(s) a programar:
              </v-sheet>
              <v-sheet>
                <v-select multiple hide-details="auto" variant="outlined" label="Hora(s)" :items="schedulingHours" v-model="scheduling.dayhours"></v-select>
              </v-sheet>
            </v-col>
          </v-row>
        </v-card-text>
        <v-divider class="mt-2"></v-divider>
        <v-card-actions class="my-2 d-flex justify-end">
          <v-btn
            class="text-none"
            text="Cancelar"
            @click="dialogs.scheduling = false"
          ></v-btn>
          <v-btn
            class="text-none"
            color="primary"
            text="Enviar"
            variant="outlined"
            @click="dialogs.scheduling = false"
            :disabled="!formCompleted"
            append-icon="mdi-send"
          ></v-btn>
        </v-card-actions>
      </v-card>
    </template>
  </v-dialog>
</template>

<script setup>
import { ref, defineProps, computed, watch } from 'vue';
import UiTitleCard from '@/components/shared/UiTitleCard.vue';
// Obtener las props
const props = defineProps({apiState: Object});
const dialogs = ref({scheduling: false});
const scheduling = ref({
  weekdays: [],
  dayhours: [],
  frequencyType: null,
});
const schedulingHours = ref([
  '00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00',
  '12:00','13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '23:59',
]);
const switchProgrammedDay = (dayNumber) => {
  if( !scheduling.value.weekdays.includes(dayNumber) ){
    scheduling.value.weekdays.push(dayNumber);
  }else{
    scheduling.value.weekdays.splice( scheduling.value.weekdays.indexOf(dayNumber) , 1)
  }
}
const apiStateReactive = ref(props.apiState);
const formCompleted = computed(() => {
  const flag = false;
  if( scheduling.value.frequencyType == 'Semanal'){
    return scheduling.value.dayhours.length && scheduling.value.weekdays.length
  } else if ( scheduling.value.frequencyType == 'Diaria'){
    return scheduling.value.dayhours.length
  }
})
const rangehours = [9,10,11,12,13,14,15,16,17,18,19,20,21];
const saturdayEnabled = ref(false);
const sundayEnabled = ref(false);
const zones = ref([
  {name: 'Colina', enabled: false, cost: 4000},
  {name: 'Lo Barnechea', enabled: false, cost: 4000},
  {name: 'Quilicura', enabled: false, cost: 4000},
  {name: 'Huechuraba', enabled: false, cost: 4000},
  {name: 'Vitacura', enabled: false, cost: 4000},
  {name: 'Pudahuel', enabled: false, cost: 4000},
  {name: 'Anillo Oeste', enabled: false, cost: 4000},
  {name: 'Anillo Este', enabled: false, cost: 4000},
  {name: 'Las Condes de Santiago', enabled: false, cost: 4000},
  {name: 'La Reina', enabled: false, cost: 4000},
  {name: 'Peñalolén', enabled: false, cost: 4000},
  {name: 'La Florida', enabled: false, cost: 4000},
  {name: 'Puente Alto', enabled: false, cost: 4000},
  {name: 'La Pintana', enabled: false, cost: 4000},
  {name: 'El Bosque', enabled: false, cost: 4000},
  {name: 'San Bernardo', enabled: false, cost: 4000},
  {name: 'Padre Hurtado', enabled: false, cost: 4000},
  {name: 'Maipú', enabled: false, cost: 4000},
])
const allZones = ref(false)
watch( allZones, (newValue,oldValue) => {
  if( newValue && !oldValue ){
    zones.value.forEach( e => e.enabled = true);
  }else if( !newValue && oldValue ){
    zones.value.forEach( e => e.enabled = false);
  }
})
</script>
