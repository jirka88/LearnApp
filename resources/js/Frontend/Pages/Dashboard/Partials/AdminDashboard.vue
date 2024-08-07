<template>
    <div class="d-flex ga-6 flex-column dashboard">
        <h1
            class="text-h3 font-weight-bold"
            :class="{ 'text-center': $vuetify.display.mdAndDown }"
        >
            {{ $t('dashboard.stats') }}
        </h1>
        <v-row
            class="d-flex"
            :class="{ 'flex-column': $vuetify.display.mdAndDown }"
        >
            <v-col>
                <WelcomeBox />
            </v-col>
            <v-col>
                <ProjectInfoBox />
            </v-col>
        </v-row>
    </div>
    <div class="py-4 d-flex flex-column">
        <Bar id="my-chart-id" :options="chartOptions" :data="chartData" />
        <div
            class="d-flex w-100 ga-4 py-8 text-center"
            :class="{ 'flex-column': $vuetify.display.mdAndDown }"
        >
            <v-card elevation="4">
                <v-card-title class="font-weight-bold">
                    {{ $t('dashboard.users') }}:
                </v-card-title>
                <v-card-text>
                    {{ stats.users.get_count_users }}
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
            <v-card elevation="4">
                <v-card-title class="font-weight-bold text-center">
                    Počet všech poznámek:
                </v-card-title>
                <v-card-text class="text-center">
                    {{ stats.chapters }}
                </v-card-text>
            </v-card>
        </div>
    </div>
    <v-row
        class="d-flex"
        :class="{ 'flex-column': $vuetify.display.mdAndDown }"
    >
        <v-col>
            <v-sheet
                :elevation="8"
                border
                min-height="10em"
                rounded
                class="py-8 px-8 d-flex justify-center align-center flex-column"
            >
                <div class="text-h6 font-weight-bold">
                    {{ $t('dashboard.restrict_register') }}
                </div>
                <v-switch
                    v-model="restrictRegister"
                    inset
                    color="green"
                    @change="() => (restrictRegisterModal = true)"
                    hide-details
                ></v-switch>
            </v-sheet>
        </v-col>
        <v-col>
            <v-sheet
                :elevation="8"
                border
                min-height="10em"
                rounded
                class="py-8 px-8 d-flex justify-center align-center"
            >
                <v-btn @click="setColorTheme" color="green">
                    Změnit barvu aplikace
                </v-btn>
            </v-sheet>
        </v-col>
        <v-col> </v-col>
    </v-row>
    <DialogChangeColorTheme
        v-if="themeModal"
        v-model="themeModal"
        @close="themeModal = false"
    >
    </DialogChangeColorTheme>
    <DialogRegisterRestrict
        v-if="restrictRegisterModal"
        v-model="restrictRegisterModal"
        :restricted="restrictRegister"
        @close="restrictRegisterModal = false"
    >
    </DialogRegisterRestrict>
</template>

<script setup>
import Chart from 'chart.js/auto'
import { defineAsyncComponent, markRaw, ref } from 'vue'
import { Bar } from 'vue-chartjs'
import WelcomeBox from '@/Frontend/Components/Dashboard/WelcomeBox.vue'
import ProjectInfoBox from '@/Frontend/Components/Dashboard/ProjectInfoBox.vue'

const DialogChangeColorTheme = defineAsyncComponent(
    () => import('@/Frontend/Components/Dashboard/DialogChangeColorTheme.vue')
)
const DialogRegisterRestrict = defineAsyncComponent(
    () => import('@/Frontend/Components/Dashboard/DialogRegisterRestrict.vue')
)

const props = defineProps(['stats'])

const restrictRegister = ref(props.stats.restrictRegister)
const restrictRegisterModal = ref(false)

const chartOptions =
    markRaw[
        {
            responsive: true
        }
    ]
const chartData = ref({
    datasets: [
        {
            data: [
                props.stats.normalUsers,
                props.stats.testersCount,
                props.stats.operators
            ],
            label: 'Uživatelé'
        }
    ],
    labels: ['Běžný uživatelé', 'Testeři', 'Operátoři']
})

const themeModal = ref(false)
const setColorTheme = () => {
    themeModal.value = true
}
</script>

<style lang="scss">
@use 'vuetify/lib/styles/settings/variables' as *;

.v-card {
    box-sizing: border-box;
    padding: 3em 0 !important;
    flex-grow: 1;
    @media #{map-get($display-breakpoints, 'md-and-down')} {
        padding: 2em 0 !important;
    }

    .v-card-text {
        font-size: 1.4em;
    }
}

#my-chart-id {
    max-height: 500px !important;
}

.v-switch__track {
    background: red;
}

.underlineLink:before {
    position: absolute;
    content: '';
    bottom: 0;
    width: 100%;
    height: 0.1em;
    background-color: #4398f0;
    transition:
        opacity 300ms,
        transform 300ms;
    transform: scale(0);
    transform-origin: center;
}

.underlineLink:hover:before {
    transform: scale(1) !important;
}
</style>
