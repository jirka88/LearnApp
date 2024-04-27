<template>
    <div class="d-flex ga-6 flex-column dashboard">
        <h1 class="text-h3 font-weight-bold" :class="{'text-center': $vuetify.display.mdAndDown}">
            {{ $t('dashboard.stats') }}</h1>
        <v-row class="d-flex" :class="{'flex-column': $vuetify.display.mdAndDown}">
            <v-col>
                <WelcomeBox/>
            </v-col>
            <v-col>
                <ProjectInfoBox/>
            </v-col>
        </v-row>
    </div>
</template>
<script setup>
import WelcomeBox from "@/Frontend/Components/Dashboard/WelcomeBox.vue";
import ProjectInfoBox from "@/Frontend/Components/Dashboard/ProjectInfoBox.vue";
</script>
<style scoped lang="scss">

</style>
