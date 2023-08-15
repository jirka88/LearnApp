<template>
    <component :is="DashboardLayout">
        <div class="vh-calc">
            <v-container>
                <div class="d-flex justify-content-between align-center pa-5 gp-em-05"
                     :class="{'flex-column-reverse': $vuetify.display.xs}">
                    <div class="d-flex flex-1-1-100 flex-wrap gp-em-05"
                         :class="{'justify-center': $vuetify.display.xs}">
                        <Link :href="route('chapter.create', subject.slug )">
                            <v-btn
                                class="bg-green">
                                Vytvořit kapitolu
                            </v-btn>
                        </Link>
                        <v-btn
                            class="bg-orange text-white"
                            @click="enableSharing">
                            Nasdílet {{ this.$page.props.user.typeAccount == 'Osobní' ? 'Sekci' : 'Předmět' }}
                        </v-btn>
                    </div>
                    <div class="d-flex flex-1-1-100 w-100 justify-end" :class="{'justify-center': $vuetify.display.xs}">
                        <v-autocomplete
                            v-model="selectedChapter"
                            variant="outlined"
                            :items="selectedChapters"
                            item-title="name"
                            item-value="id"
                            prepend-inner-icon="mdi-folder-search-outline">

                        </v-autocomplete>
                    </div>
                </div>
                <main class="pa-5 d-flex flex-wrap" :class="{'justify-center': $vuetify.display.smAndDown}">
                    <v-dialog
                        v-model="status"
                        persistent
                        width="auto"
                    >
                        <v-card>
                            <v-card-title class="text-h5 text-center">
                                Opravdu si přejete smazat kapitolu <strong>{{ activeChapter.name }}</strong>
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
                    <v-dialog
                        v-model="sharing"
                        persistent
                        width="auto"
                    >
                        <v-form @submit.prevent="sharingToUsers">
                            <v-card>
                                <v-card-title class="text-h5 text-center">
                                    Komu si přejete nasdílet tuto sekci?
                                </v-card-title>
                                <v-autocomplete
                                    v-model="selectedUsers"
                                    chips
                                    variant="outlined"
                                    label="Emailová adresa uživatelů"
                                    :items="users.map(item => item.email)"
                                    multiple
                                    hide-details
                                    class="pa-2"
                                >
                                </v-autocomplete>
                                <p class="text-center pa-2 text-red">{{ errors.users }}</p>
                                <v-card-text class="text-center pa-1">Až uživatel příjme nasdílení dostane k ní
                                    přístup!
                                </v-card-text>
                                <v-card-actions class="margin-center d-flex justify-center">
                                    <v-btn
                                        class="bg-white"
                                        @click="sharing = false"
                                        size="x-large"
                                    >
                                        Zřušit
                                    </v-btn>
                                    <v-btn
                                        type="submit"
                                        class="bg-orange"
                                        size="x-large"
                                    >
                                        Nasdílet!
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-form>
                    </v-dialog>
                    <v-card
                        v-if="selectedChapter === undefined"
                        v-for="chapter in chapters" :key="chapter.id"
                        class="pa-2 d-flex flex-column elevation-20"
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
                        <v-card-actions class="justify-end align-center gp-em-05">
                            <v-btn
                                icon="mdi-trash-can"
                                variant="flat"
                                color="red"
                                @click="enableDialog(chapter)"
                            >
                            </v-btn>
                            <Link :href="route('chapter.edit', {slug: subject.slug, chapter: chapter.slug})">
                                <v-btn
                                    icon="mdi-pencil"
                                    variant="flat"
                                    color="green"
                                >
                                </v-btn>
                            </Link>
                            <Link :href="route('chapter.show', {chapter: chapter.id, slug: subject.slug})">
                                <v-btn
                                    icon="mdi-near-me"
                                    variant="flat"
                                    color="green"
                                >
                                </v-btn>
                            </Link>
                        </v-card-actions>
                    </v-card>
                    <v-card
                        v-else
                        class="pa-2 d-flex flex-column elevation-20"
                        max-width="344"
                    >

                        <v-card-text>
                            <p class="text-h4 font-weight-bold text--primary py-4">
                                {{ selectedChapterShow.name }}
                            </p>
                            <div class="text--primary">
                                {{ selectedChapterShow.perex }}<br>
                            </div>
                        </v-card-text>
                        <v-card-actions class="justify-end align-center gp-em-05">
                            <v-btn
                                icon="mdi-trash-can"
                                variant="flat"
                                color="red"
                                @click="enableDialog(selectedChapterShow)"
                            >
                            </v-btn>
                            <Link :href="route('chapter.edit', {slug: subject.slug, chapter: selectedChapterShow.slug})">
                                <v-btn
                                    icon="mdi-pencil"
                                    variant="flat"
                                    color="green"
                                >
                                </v-btn>
                            </Link>
                            <Link :href="route('chapter.show', {chapter: selectedChapterShow.id, slug: subject.slug})">
                                <v-btn
                                    icon="mdi-near-me"
                                    variant="flat"
                                    color="green"
                                >
                                </v-btn>
                            </Link>
                        </v-card-actions>
                    </v-card>
                </main>
            </v-container>
        </div>
    </component>
</template>

<script setup>

import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {Link} from "@inertiajs/inertia-vue3";
import {ref, watch} from "vue";
import {Inertia} from "@inertiajs/inertia";
import axios from "axios";
import {useForm} from "@inertiajs/inertia-vue3";

const status = ref(false);
const sharing = ref(false);
const activeChapter = ref("");
const users = ref();
const selectedUsers = ref();
const props = defineProps({chapters: Object, subject: Object, users: Object, errors: Object});
const selectedChapters = props.chapters;

const selectedChapter = ref();
const selectedChapterShow = ref();

watch(selectedChapter, () => {
    selectedChapterShow.value = props.chapters.find(x => x.id === selectedChapter.value);
    if(selectedChapterShow.value === undefined) {
        selectedChapter.value = undefined;
        console.log(selectedChapter);
    }
})
const form = useForm({
    users: selectedUsers,
});

const rules = {
    required: v => v.length < 0 || "Musíte zadat uživatele",
}
const enableSharing = async () => {
    await axios.get(route('sharing'))
        .then(response => {
            users.value = response.data;
            console.log(users);
        })
    sharing.value = true;
}
const enableDialog = (chapter) => {
    activeChapter.value = chapter;
    status.value = true;
}
const destroy = () => {
    Inertia.delete(route('chapter.destroy', {slug: props.subject.slug, chapter: activeChapter.value.slug}));
    status.value = false;
}
const sharingToUsers = () => {
    form.post(route('share'), {
        onSuccess: () => {
            status.value = false;
        }
    })
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

.v-dialog {
    .v-card-title {
        white-space: unset;
    }
}

.v-autocomplete {
    max-width: 200px !important;
}
</style>
