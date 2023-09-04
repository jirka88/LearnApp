<template>
    <component :is="DashboardLayout">
        <div v-if="showSearchMobile" class="hidden-md-and-up justify-center pa-5 w-100 justify-end position-sticky" id="m-box"  data-aos="zoom-in-down">
            <v-autocomplete
                v-model="selectedChapter"
                variant="outlined"
                :items="selectedChapters"
                item-title="name"
                item-value="id"
                class="w-100 searchMobile"
                prepend-inner-icon="mdi-folder-search-outline"
                hide-details>
            </v-autocomplete>
        </div>
            <v-container v-scroll="onScroll">
                <div class="d-flex justify-content-between align-center pa-5 gp-em-05"
                     :class="{'flex-column-reverse': $vuetify.display.xs}">
                    <div class="d-flex flex-1-1-100 flex-wrap gp-em-05"
                         :class="{'justify-center': $vuetify.display.xs}">
                        <Link v-if="subject.permission.permission_id != 1" :href="route('chapter.create', subject.slug )" data-aos="zoom-in"  data-aos-duration="400">
                            <v-btn
                                class="bg-green">
                                Vytvořit kapitolu
                            </v-btn>
                        </Link>
                        <v-btn
                            data-aos="zoom-in"  data-aos-duration="400"
                            v-if="subject.created_by == this.$page.props.user.id"
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
                            class="search"
                            prepend-inner-icon="mdi-folder-search-outline">
                        </v-autocomplete>
                    </div>
                </div>
                <selection class="pa-5 d-flex flex-wrap" :class="{'justify-center': $vuetify.display.smAndDown}">
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
                        v-if="subject.created_by == this.$page.props.user.id"
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
                                <p class="text-center pa-1 text-red">{{ errors.users }}</p>
                                <p class="text-center pa-1 text-red">{{ errors.permission }}</p>
                                <div class="d-flex">
                                    <v-checkbox
                                        v-model="permission"
                                        label="Pouze k přečtení"
                                        value="1">
                                    </v-checkbox>
                                    <v-checkbox
                                        v-model="permission"
                                        label="K přečtení a úpravě"
                                        value="2">
                                    </v-checkbox>
                                    <v-checkbox
                                        v-model="permission"
                                        label="Plná kontrola"
                                        value="3">
                                    </v-checkbox>
                                </div>

                                <v-card-text class="text-center pa-1">Až uživatel příjme nasdílení dostane k ní
                                    přístup!
                                </v-card-text>
                                <p v-if="$page.props.flash.messageUpdate" class="text-center text-green pa-2 font-weight-bold">
                                    {{ $page.props.flash.messageUpdate }}</p>
                                <v-card-actions class="margin-center d-flex justify-center">
                                    <v-btn
                                        class="bg-white"
                                        @click="sharing = false; $page.props.flash.messageUpdate = ''"
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
                        data-aos="zoom-in" data-aos-delay="200" data-aos-duration="300"
                        data-aos-anchor-placement="top-bottom"
                        :class="{'w-50': $vuetify.display.sm}"
                        v-if="selectedChapter === undefined"
                        v-for="chapter in chapters" :key="chapter.id"
                        class="pa-2 d-flex flex-column elevation-20"
                    >
                        <v-card-text>
                            <p class="text-h4 font-weight-bold text--primary py-4">
                                {{ chapter.name }}
                            </p>
                            <div class="text--primary">
                                {{ chapter.perex }}<br>
                            </div>
                        </v-card-text>
                        <v-card-actions class="flex-wrap justify-end align-center gp-em-05">
                            <v-btn
                                v-if="Number(subject.permission.permission_id) !== 1"
                                icon="mdi-trash-can"
                                variant="flat"
                                color="red"
                                @click="enableDialog(chapter)"
                            >
                            </v-btn>
                            <Link v-if="Number(subject.permission.permission_id) !== 1" :href="route('chapter.edit', {slug: subject.slug, chapter: chapter.slug})">
                                <v-btn
                                    icon="mdi-pencil"
                                    variant="flat"
                                    color="blue"
                                >
                                </v-btn>
                            </Link>
                            <Link :href="route('chapter.show', {chapter: chapter.slug, slug: subject.slug})">
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
                        data-aos="zoom-in" data-aos-delay="200" data-aos-duration="300"
                        data-aos-anchor-placement="top-bottom"
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
                                v-if="Number(subject.permission.permission_id) !== 1"
                                icon="mdi-trash-can"
                                variant="flat"
                                color="red"
                                @click="enableDialog(selectedChapterShow)"
                            >
                            </v-btn>
                            <Link
                                v-if="Number(subject.permission.permission_id) !== 1"
                                :href="route('chapter.edit', {slug: subject.slug, chapter: selectedChapterShow.slug})">
                                <v-btn
                                    icon="mdi-pencil"
                                    variant="flat"
                                    color="blue"
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
                </selection>
                <v-pagination
                    class="pa-8"
                    v-model="page"
                    :length="pages"

                    prev-icon="mdi-menu-left"
                    next-icon="mdi-menu-right"
                    @update:modelValue="fetchData"
                ></v-pagination>
            </v-container>
    </component>
</template>

<script setup>

import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {Link} from "@inertiajs/inertia-vue3";
import {ref, watch} from "vue";
import {Inertia} from "@inertiajs/inertia";
import axios from "axios";
import {useForm} from "@inertiajs/inertia-vue3";

const page = ref(0);
const pages = ref(1);

const status = ref(false);
const sharing = ref(false);
const activeChapter = ref("");
const users = ref();
const selectedUsers = ref();

const showSearchMobile = ref(false);

const props = defineProps({chapters: Object, subject: Object, users: Object, errors: Object});
const selectedChapters = props.chapters;
const permission = ref();

const selectedChapter = ref();
const selectedChapterShow = ref();

watch(selectedChapter, () => {
    selectedChapterShow.value = props.chapters.find(x => x.id === selectedChapter.value);
    if (selectedChapterShow.value === undefined) {
        selectedChapter.value = undefined;
    }
})
const form = useForm({
    users: selectedUsers,
    permission: permission,
    subject: props.subject.id
});

const rules = {
    required: v => v.length < 0 || "Musíte zadat uživatele",
}
const enableSharing = async () => {
    await axios.get( props.subject.slug + "/sharing/users")
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
            selectedUsers.value = null;
        }
    })
}
const isOn = ref(false);
const onScroll = () => {
    if(window.scrollY > 120) {
        showSearchMobile.value = true;
        isOn.value = true;
    }
    else {
        if(isOn) {
            isOn.value = false;
            showSearchMobile.value = false;
        }
    }
}
const fetchData = () => {

}
</script>
<style scoped lang="scss">
selection {
    gap: 2.5em;
    .v-card {
        flex: 1 1 auto;
        width: 260px !important;
        min-height: 14em !important;
        .text-h4 {
            text-wrap: balance;
        }
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
.search {
    max-width: 200px !important;
}
#m-box {
    display: flex;
    width: 100%;
    background: white !important;
    top: 4em;
    z-index: 1;
}
.v-autocomplete {
    max-width: 500px;
}

:deep(.v-selection-control) {
    display: flex !important;
    flex-direction: column !important;
    flex: unset !important;
}
</style>
