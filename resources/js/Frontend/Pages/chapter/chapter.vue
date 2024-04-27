<template>
    <component :is="DashboardLayout">
        <div class="chapterBg">
            <v-container class="py-12">
                <div class="chapter pa-10 elevation-20">
                <ChapterSettings
                    :chapter="chapter"
                    :slug="slug"
                    @modal="status = true">
                    </ChapterSettings>
                <v-divider></v-divider>
                <p class="text-h2 py-4 font-weight-bold">{{ chapter.name }}</p>
                <p class="text-h6 py-2">{{ chapter.perex }}</p>
                <v-divider class="py-2"></v-divider>
                <p class="text-left" v-html="chapter.context"></p>
                </div>
            </v-container>
            <v-dialog
                v-model="status"
                persistent
                width="auto"
            >
                <v-card>
                    <v-card-title class="text-h5 text-center">
                        Opravdu si přejete smazat kapitolu <strong>{{ chapter.name }}</strong>
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
        </div>
    </component>
</template>

<script setup>
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {ref} from "vue";
import ChapterSettings from "@/Frontend/Components/ChapterSettings.vue";
import {Inertia} from "@inertiajs/inertia";
const status = ref(false);
const props = defineProps({chapter: Object, slug: String})
const destroy = () => {
    Inertia.delete(route('chapter.destroy', {slug: props.slug, chapter: props.chapter.slug}));
    emit('delete');
}

</script>

<style scoped lang="scss">

.chapterBg {
    background: #4398f0 !important;
    .chapter {
        border-radius: 24px;
        background: white !important;
        min-height: calc(100vh - 112px - 32px);

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
