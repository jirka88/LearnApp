<template>
    <component :is="DashboardLayout">
        <main class="primary-bg">
            <v-container>
                <div class="chapter pa-10 elevation-20">
                    <BackBtn class="pb-4" :url="route('subject.show', slug)"/>
                    <v-divider ></v-divider>
                    <p class="text-h2 py-4 font-weight-bold">{{ chapter.name }}</p>
                    <p class="text-h6 py-2">{{ chapter.perex }}</p>
                    <v-divider class="py-2"></v-divider>
                    <p class="text-left" v-html="chapter.context"></p>
                </div>
            </v-container>
        </main>
    </component>
</template>

<script setup>
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import BackBtn from "@/Frontend/Components/UI/BackBtn.vue";

defineProps({chapter: Object, slug: String})
</script>

<style lang="scss">
main {
    .chapter {
        border-radius: 24px;
        background: white;
        min-height: calc(100vh - 64px - 32px);
        h2 {
            text-wrap: balance;
        }
    }
}
:deep(.ql-syntax) {
    background: black;
    padding: 2em;
    border-radius: 24px;
    color: white;
}
</style>
