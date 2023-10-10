<template>
    <component :is="DashboardLayout">
        <main class="primary-bg">
            <v-container>
                <div class="chapter pa-10 elevation-20">
                    <div class="d-flex gp-em-05">
                        <BackBtn class="pb-4" :url="route('subject.show', slug)" background='green' data-aos="zoom-in"
                                 data-aos-duration="400"/>
                        <Link v-if="chapter.partition.users[0].permission.permission_id != 1"
                              :href="route('chapter.edit',  {slug: slug, chapter: chapter.slug})" data-aos="zoom-in"
                              data-aos-duration="400">
                            <v-btn
                                icon="mdi-pencil"
                                variant="flat"
                                color="blue"
                                size="large">
                            </v-btn>
                        </Link>
                        <v-btn
                            v-if="chapter.partition.users[0].permission.permission_id != 1"
                            data-aos="zoom-in" data-aos-duration="400"
                            icon="mdi-trash-can"
                            variant="flat"
                            color="red"
                            size="large"
                            @click="enableDialog">
                        </v-btn>
                    </div>
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
        </main>
    </component>

</template>

<script setup>
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import BackBtn from "@/Frontend/Components/UI/BackBtn.vue";
import {Link} from "@inertiajs/inertia-vue3";
import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({chapter: Object, slug: String})
const status = ref(false);
const enableDialog = (chapter) => {
    status.value = true;
}
const destroy = () => {
    Inertia.delete(route('chapter.destroy', {slug: props.slug, chapter: props.chapter.slug}));
    status.value = false;
}
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
