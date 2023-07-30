<template>
    <component :is="DashboardLayout">
        <v-container>
        <div class="d-flex flex-column pa-5 gp-em-4">
            <div class="btns d-flex align-center">
                <Link :href="route('chapter.create', subject.slug )">
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
        <main class="pa-5 d-flex flex-wrap">
            <v-dialog
                v-model="status"
                persistent
                width="auto"
            >
                <v-card>
                    <v-card-title class="text-h5 text-center">
                        Opravdu si přejete smazat kapitolu <strong>{{activeChapter.name}}</strong>
                    </v-card-title>
                    <v-card-text class="text-center">Tato akce je nenávratná!</v-card-text>
                    <v-card-actions class="margin-center">
                        <v-spacer></v-spacer>
                        <v-btn
                            class="bg-white"
                            @click="status = false"
                            size="x-large"
                        >
                            Zřušit
                        </v-btn>
                        <v-btn
                            class="bg-red"
                            @click="destroy()"
                            size="x-large"
                        >
                            Smazat!
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
                <v-card
            v-for="chapter in chapters" :key="chapter.id"
            class="pa-2 d-flex flex-column"
            max-width="344"
        >
            <v-card-text>
                <p class="text-h4 font-weight-bold text--primary py-4">
                    {{ chapter.name }}
                </p>
                <div class="text--primary">
                    {{ chapter.perex }}<br>
                </div>
            </v-card-text>
            <v-card-actions class="justify-end align-center">
                <v-btn
                    icon="mdi-trash-can"
                    variant="flat"
                    color="red"
                    @click="enableDialog(chapter)"
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
    </component>
</template>

<script setup>

import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {Link} from "@inertiajs/inertia-vue3";
import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
const status = ref(false);
const activeChapter = ref("");
const props = defineProps({chapters: Object, subject: Object});

const enableDialog = (chapter) => {
    activeChapter.value = chapter;
    status.value = true;
}
const destroy = () => {
    Inertia.delete(route('chapter.destroy', {slug: props.subject.slug, chapter: activeChapter.value.slug}));
    status.value = false;
}
</script>
<style scoped lang="scss">
main {
    gap: 1em;
    .v-card {
        flex: 1 1 auto;
        width: 350px !important;
        min-height: 14em !important;
        .v-card-actions {
            .v-btn {
                border-radius: 0.5em !important;
            }
        }
    }
}

.v-select {
    max-width: 150px;
}
.btns {
    justify-content: space-between;
}
.v-dialog {
    .v-card-title {
        white-space: unset;
    }
}
</style>
