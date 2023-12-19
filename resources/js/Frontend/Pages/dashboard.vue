<script setup>
import DashboardLayout from "../layouts/DashboardLayout.vue";
import Chart from 'chart.js/auto';
import { Bar } from 'vue-chartjs'
import {markRaw, ref} from "vue";
const props = defineProps(['stats'])

const chartOptions = markRaw[{
    responsive: true}
]
const chartData = ref({datasets: [ {data: [props.stats.normalUsers, props.stats.testersCount , props.stats.operators], label: 'Uživatelé'}],labels: ["Běžný uživatelé", "Testeři", "Operátoři"]});

</script>

<template>
    <component :is="DashboardLayout">
            <v-container class="py-8">
                <h1 class="text-h3 font-weight-bold" :class="{'text-center': $vuetify.display.mdAndDown}">{{$t('dashboard.stats')}}</h1>
                <template v-if="$page.props.user.role.id === 1">
                    <div class="py-4 d-flex flex-column">
                        <Bar
                            id="my-chart-id"
                            :options="chartOptions"
                            :data="chartData"
                        />
                        <div class="d-flex w-100 ga-4 py-8 text-center" :class="{'flex-column': $vuetify.display.mdAndDown}" >
                            <v-card elevation="4">
                                <v-card-title class="font-weight-bold">
                                    Uživatelů:
                                </v-card-title>
                                <v-card-text>
                                    {{stats.users}}
                                </v-card-text>
                            </v-card>
                            <v-card elevation="4">
                                <v-card-title class="font-weight-bold">
                                    Operátorů:
                                </v-card-title>
                                <v-card-text>
                                    {{stats.operators}}
                                </v-card-text>
                            </v-card>
                            <v-card elevation="4">
                                <v-card-title class="font-weight-bold">
                                    Testerů:
                                </v-card-title>
                                <v-card-text>
                                    {{stats.testersCount}}
                                </v-card-text>
                            </v-card>
                            <v-card elevation="4">
                                <v-card-title class="font-weight-bold">
                                    Běžných uživatelů:
                                </v-card-title>
                                <v-card-text>
                                    {{stats.normalUsers}}
                                </v-card-text>
                            </v-card>
                        </div>
                    </div>
                </template>
            </v-container>
    </component>
</template>

<style lang="scss">
.v-app-bar{
    .v-icon {
        color: black !important;
    }
}
.v-card {
    box-sizing: border-box;
    padding: 4em 0 !important;
    flex-grow: 1;
    .v-card-text {
        font-size: 1.4em;
    }
}
#my-chart-id {
    max-height: 500px !important;
}
</style>
