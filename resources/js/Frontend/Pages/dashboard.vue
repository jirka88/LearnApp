<script setup>
import DashboardLayout from "../layouts/DashboardLayout.vue";
import Chart from 'chart.js/auto';
import {Bar} from 'vue-chartjs'
import {defineAsyncComponent, markRaw, ref, watch} from "vue";
import WelcomeBox from "@/Frontend/Components/Dashboard/WelcomeBox.vue";
const DialogRegisterRestrict = defineAsyncComponent(() => import ("@/Frontend/Components/Dashboard/DialogRegisterRestrict.vue"));

const props = defineProps(['stats'])

const restrictRegister = ref(props.stats.restrictRegister);

const restrictRegisterModal = ref(false);

const chartOptions = markRaw[{
    responsive: true
}
    ]
const chartData = ref({
    datasets: [{
        data: [props.stats.normalUsers, props.stats.testersCount, props.stats.operators],
        label: 'Uživatelé'
    }], labels: ["Běžný uživatelé", "Testeři", "Operátoři"]
});

</script>

<template>
    <component :is="DashboardLayout">
        <v-container class="py-8">
            <template v-if="$page.props.user.role.id === 1">
                <div class="d-flex ga-6 flex-column dashboard">
                    <h1 class="text-h3 font-weight-bold" :class="{'text-center': $vuetify.display.mdAndDown}">
                        {{ $t('dashboard.stats') }}</h1>
                    <v-row class="d-flex" :class="{'flex-column': $vuetify.display.mdAndDown}">
                        <v-col>
                            <WelcomeBox></WelcomeBox>
                        </v-col>
                        <v-col>
                            <v-sheet
                                :elevation="8"
                                :height="80"
                                border
                                rounded
                                class="d-flex justify-center align-center flex-column py-2"
                            >
                                <div class="text-h5 font-weight-bold">LearnApp</div>
                                <a href="http://github.com" class="underlineLink" target="_blank">Github</a>
                            </v-sheet>
                        </v-col>
                    </v-row>
                </div>
                <div class="py-4 d-flex flex-column">
                    <Bar
                        id="my-chart-id"
                        :options="chartOptions"
                        :data="chartData"
                    />
                    <div class="d-flex w-100 ga-4 py-8 text-center"
                         :class="{'flex-column': $vuetify.display.mdAndDown}">
                        <v-card elevation="4">
                            <v-card-title class="font-weight-bold">
                                {{ $t('dashboard.users') }}:
                            </v-card-title>
                            <v-card-text>
                                {{ stats.users }}
                            </v-card-text>
                        </v-card>
                        <v-card elevation="4">
                            <v-card-title class="font-weight-bold">
                                {{ $t('dashboard.operators') }}:
                            </v-card-title>
                            <v-card-text>
                                {{ stats.operators }}
                            </v-card-text>
                        </v-card>
                        <v-card elevation="4">
                            <v-card-title class="font-weight-bold">
                                {{ $t('dashboard.testers') }}:
                            </v-card-title>
                            <v-card-text>
                                {{ stats.testersCount }}
                            </v-card-text>
                        </v-card>
                        <v-card elevation="4">
                            <v-card-title class="font-weight-bold">
                                {{ $t('dashboard.normal_users') }}:
                            </v-card-title>
                            <v-card-text>
                                {{ stats.normalUsers }}
                            </v-card-text>
                        </v-card>
                    </div>
                </div>
                <v-row class="d-flex" :class="{'flex-column': $vuetify.display.mdAndDown}">
                    <v-col>
                        <v-sheet
                            :elevation="8"
                            border
                            rounded
                             class="py-8 px-8 d-flex justify-center align-center flex-column">
                            <div class="text-h6 font-weight-bold">Omezit registraci</div>
                            <v-switch v-model="restrictRegister" inset color="green" @change="(() => restrictRegisterModal = true)" hide-details></v-switch>
                        </v-sheet>
                    </v-col>
                    <v-col>
                        <v-sheet
                            :elevation="8"
                            border
                            rounded
                        >
                        </v-sheet>
                    </v-col>
                    <v-col>
                        <v-sheet
                            :elevation="8"
                            border
                            rounded
                        >
                        </v-sheet>
                    </v-col>
                </v-row>
                <DialogRegisterRestrict v-model="restrictRegisterModal" :restricted="restrictRegister" @close="restrictRegisterModal = false;  restrictRegister = stats.restrictRegister;"></DialogRegisterRestrict>
            </template>
        </v-container>
    </component>
</template>

<style lang="scss">
.v-app-bar {
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
.v-switch__track {
    background: red !important;
}
.underlineLink:before {
    position: absolute;
    content: '';
    bottom: 0;
    width: 100%;
    height: 0.1em;
    background-color: #4398f0;
    transition: opacity 300ms, transform 300ms;
    transform: scale(0);
    transform-origin: center;
}
.underlineLink:hover:before {
    transform: scale(1) !important;
}
</style>
