<template>
    <component :is="DashboardLayout">
        <div class="creatingChapter d-flex justify-center align-center primary-bg">
            <v-container class="pa-8">
                <BackBtn :url="route('subject.show', slug)"/>
                <form class="pa-8 py-4 mt-4 w-100 d-flex flex-column elevation-20" @submit.prevent="createChapter">
                    <h1 class="py-4">Vytvoření Kapitoly</h1>
                    <v-text-field
                        v-model="form.name"
                        variant="outlined"
                        :label="$t('global.name')"
                        :rules="[rules.required, rules.nameLength]"
                        :error="errors.name"
                        required
                    ></v-text-field>
                    <v-text-field
                        v-model="form.perex"
                        variant="outlined"
                        label="Perex"
                        :rules="[rules.perexLength]"
                    ></v-text-field>
                    <v-no-ssr>
                        <QuillEditor v-model:content="form.contentChapter"
                                     theme="snow"
                                     toolbar="full"
                                     content-type="html"
                                     :style="form.errors.contentChapter ? { 'border': '1px solid red !important' } : {}"
                        />
                        <p class="text-red pt-1 subtitle-2">{{ form.errors.contentChapter }}</p>
                    </v-no-ssr>
                    <v-btn type="submit"
                           color="blue"
                           class="btn d-flex my-4"
                           :disabled="btnStatus"
                           :class="{'w-100': $vuetify.display.smAndDown}"
                    >
                        {{ $t('global.created') }}!
                    </v-btn>
                </form>
                <Toastify v-if="isActiveToast && (form.errors.name || form.errors.message)"
                          :text="form.errors.name ? (form.errors.name + ' ') : '' + (form.errors.message ? form.errors.message : '')"
                          variant="error" :time="3000" @close="toastShow(false)"></Toastify>
            </v-container>
        </div>
    </component>
</template>

<script setup>

import {useForm} from "@inertiajs/inertia-vue3";
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {QuillEditor} from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import BackBtn from "@/Frontend/Components/UI/BackBtn.vue";
import rules from "./../../rules/rules"
import {isActiveToast, toastShow} from "@/Toast";
import Toastify from "@/Frontend/Components/UI/Toastify.vue";
import {ref} from "vue";

const props = defineProps({slug: String, errors: Object})
const form = useForm({
    name: "",
    perex: "",
    contentChapter: "",
    slug: props.slug
});
const btnStatus = ref(false);
const createChapter = () => {
    btnStatus.value = true;
    form.post(route('chapter.store', props.slug), {
        onFinish: () => {
            btnStatus.value = false;
        }
    });
}
</script>

<style lang="scss">
.creatingChapter {
    min-height: calc(100vh - 64px);
    overflow: auto;

    form {
        background: white;
        border-radius: 24px;

        .v-btn {
            margin: 0px auto;
            padding: 2em;
        }
    }
}
</style>
<style scoped lang="scss">
:deep(.ql-container) {
    height: 40vh !important;
}

:deep(.ql-snow) {
    border: 1px solid black;
}

:deep(.v-messages__message) {
    padding-bottom: 1.2em;
    text-align: left !important;
}
</style>
