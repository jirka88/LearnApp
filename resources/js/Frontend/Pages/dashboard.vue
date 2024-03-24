<script setup>
import DashboardLayout from "../layouts/DashboardLayout.vue";
import AdminDashboard from "@/Frontend/Components/Dashboard/AdminDashboard.vue";
const props = defineProps(['stats'])
</script>

<template>
    <component :is="DashboardLayout">
        <v-container class="py-8">
            <AdminDashboard v-if="$page.props.user.role.id === 1" :stats="stats" />
        </v-container>
    </component>
</template>

<style lang="scss">
@use 'vuetify/lib/styles/settings/variables' as *;
.v-app-bar {
    .v-icon {
        color: black !important;
    }
}
</style>
