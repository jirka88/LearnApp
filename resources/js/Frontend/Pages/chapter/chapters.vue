<template>
    <component :is="DashboardLayout">
        <div v-if="showSearchMobile" class="hidden-md-and-up justify-center pa-5 w-100 justify-end position-sticky"
             id="m-box" data-aos="zoom-in-down">
        </div>
        <v-container v-scroll="onScroll">
            <Breadcrumbs
                :items="[{title: 'předměty', disabled: false, to: route('subject.index') }, {title: subject.name, disabled: true }]"></Breadcrumbs>
            <div class="d-flex justify-content-between align-center py-5"
                 :class="{'flex-column-reverse': $vuetify.display.xs}">
                <div class="d-flex flex-1-1-100 flex-wrap"
                     :class="[{'justify-center ga-4': $vuetify.display.xs}, {'ga-6': $vuetify.display.smAndUp}]">
                    <Link v-if="subject.permission.permission_id != 1" :href="route('chapter.create', subject.slug )"
                          data-aos="zoom-in" data-aos-duration="400">
                        <v-btn
                            class="bg-green">
                            {{ $t('global.created') }} {{$t('global.chapter')}}
                        </v-btn>
                    </Link>
                    <v-btn
                        data-aos="zoom-in" data-aos-duration="400"
                        v-if="subject.created_by == $page.props.user.id"
                        class="bg-orange text-white"
                        @click="enableSharing">
                        Nasdílet {{ $page.props.user.typeAccount == 'Osobní' ? 'Sekci' : 'Předmět' }}
                    </v-btn>
                </div>
                <SearchChapters
                    :subject="subject"
                ></SearchChapters>
            </div>
            <v-card v-if="$page.props.user.id != subject.created_by" class="pa-4 font-weight-bold"
                    :class="{'text-center': $vuetify.display.smAndDown}">
                <p>Sdíleno od: {{ sharingUsr.firstname }} {{ sharingUsr.lastname }} ({{ sharingUsr.email }}) </p>
            </v-card>
            <v-sheet class="py-5 d-grid ga-6">
                <DialogShare
                    v-if="sharing"
                    v-model="sharing"
                    :subject="subject"
                    :errors="errors"
                    :users="users"
                    @close="sharing = false"
                />
                <ChapterPreview
                    v-for="chapter in chapters.data" :key="chapter.id"
                    :chapter="chapter"
                    :subject="subject"
                />
            </v-sheet>
            <v-pagination
                v-if="pages !== 0"
                class="pa-8"
                v-model="page"
                :length="pages"

                prev-icon="mdi-menu-left"
                next-icon="mdi-menu-right"
                @update:modelValue="fetchData"
            ></v-pagination>
        </v-container>
        <Toastify
            v-if="$page.props.flash.message ? true : false"
            :text="$page.props.flash.message"
            variant="success"
            :time="3000"></Toastify>
    </component>
</template>

<script setup>

import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {Link} from "@inertiajs/inertia-vue3";
import {defineAsyncComponent, ref, watch} from "vue";

import Breadcrumbs from "../../Components/UI/Breadcrumbs.vue";
import {Inertia} from "@inertiajs/inertia";
import ChapterPreview from "@/Frontend/Components/ChapterPreview.vue";
import axios from "axios";
import SearchChapters from "@/Frontend/Components/SearchChapters.vue";
import Toastify from "@/Frontend/Components/UI/Toastify.vue";

const users = ref();
const DialogShare = defineAsyncComponent(() => import("@/Frontend/Components/DialogShare.vue"))

const page = ref(1);
const sharing = ref(false);
const status = ref(false);
const showSearchMobile = ref(false);
const props = defineProps({
    chapters: Object,
    subject: Array,
    errors: Object,
    sharingUsr: Object,
    pages: Number,
});

const isOn = ref(false);
const enableSharing = async () => {
    await axios.get(props.subject.slug + "/sharing/users")
        .then(response => {
            users.value = response.data;
        })
    sharing.value = true;
}
const onScroll = () => {
    if (window.scrollY > 120) {
        showSearchMobile.value = true;
        isOn.value = true;
    } else {
        if (isOn) {
            isOn.value = false;
            showSearchMobile.value = false;
        }
    }
}
const fetchData = () => {
    Inertia.get(route('subject.show', props.subject.slug), {page: page.value}, {
        preserveState: true,
        onSuccess: (response) => {
            props.chapters = response.props.chapters;
        }
    });
}
</script>
<style scoped lang="scss">
@use 'vuetify/lib/styles/settings/variables' as *;

.v-sheet {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    background: none;
    @media #{map-get($display-breakpoints, 'md-and-down')} {
        grid-template-columns: 1fr 1fr;
    }
    @media #{map-get($display-breakpoints, 'sm-and-down')} {
        grid-template-columns: 1fr;
        align-items: center;
        justify-content: center;
    }
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
