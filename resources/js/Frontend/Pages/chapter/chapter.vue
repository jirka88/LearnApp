<template>
    <component :is="DashboardLayout">
        <main class="primary-bg">
            <v-container>
                <div class="chapter pa-10 elevation-20">
                    <p class="text-h2 py-2 font-weight-bold">{{ chapter.name }}</p>
                    <p class="text-h6 py-6">{{ chapter.perex }}</p>
                    <v-divider class="py-2"></v-divider>
                    <p class="text-left" v-html="chapter.context"></p>
                </div>
            </v-container>
        </main>
    </component>
</template>

<script setup>
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";

defineProps({chapter: Object})
</script>

<style lang="scss">
main {
    .chapter {
        border-radius: 24px;
        background: white;
        min-height: calc(100vh - 64px - 32px);
    }
}
:deep(.ql-syntax) {
    background: black;
    padding: 2em;
    border-radius: 24px;
    color: white;
}
</style>
