<template>
    <DashboardLayout>
        <v-container>
        <div class="d-flex flex-column pa-5 gp-em-4">
            <div class="btns d-flex align-center">
                <Link :href="route('subject.create')">
                    <v-btn
                        class="bg-green">
                        Vytvořit kapitolu
                    </v-btn>
                </Link>
                <v-select
                    label="Select"
                    persistent-hint
                    return-object
                    hide-details
                    single-line
                    variant="outlined">
                </v-select>
            </div>
        </div>
        <main class="pa-8 d-flex">
        <v-card
            v-for="chapter in chapters" :key="chapter.id"
            class="pa-2"
            max-width="344"
        >
            <v-card-text>
                <p class="text-h4 font-weight-bold text--primary py-4">
                    {{chapter.name}}
                </p>
                <div class="text--primary">
                    {{chapter.perex}}<br>
                </div>
            </v-card-text>
            <v-card-actions class="justify-end align-center">
                <v-btn
                    icon="mdi-trash-can"
                    variant="flat"
                    color="red"
                >
                </v-btn>
                <v-btn
                    icon="mdi-pencil"
                    variant="flat"
                    color="green"
                >
                </v-btn>
                <v-btn
                    icon="mdi-near-me"
                    variant="flat"
                    color="green"
                >
                </v-btn>
            </v-card-actions>

        </v-card>
        </main>
        </v-container>
    </DashboardLayout>
</template>

<script setup>

import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {Link} from "@inertiajs/inertia-vue3";

defineProps({chapters: Object});
</script>
<style scoped lang="scss">
.v-card {
    flex: 1 1 auto;
    .v-card-actions {
        .v-btn {
            border-radius: 0.5em !important;
        }
    }
}
.v-select {
    max-width: 150px;
}
.btns {
    justify-content: space-between;
}
</style>
