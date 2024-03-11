<script setup>

import DashboardLayout from "../../../layouts/DashboardLayout.vue";
import err from "./../../../../../assets/ui/errors/404-error.svg"
</script>

<template>
<dashboard-layout>
    <v-main>
        <v-container>
            <div class="err text-center bigHeader flex-column">
                <v-img class="margin-center" max-width="300" :src="err"></v-img>
                <h1>{{$t('errors.title')}}</h1>
                <p class="text-subtitle-1 font-weight-bold">{{$t('errors.404.description')}}</p>
                <p class="text-subtitle-2">{{$t('errors.404.solve')}}</p>
            </div>
        </v-container>
    </v-main>
</dashboard-layout>
</template>

<style scoped lang="scss">

</style>
