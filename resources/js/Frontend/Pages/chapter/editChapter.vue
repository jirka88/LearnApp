
<template>
    <component :is="DashboardLayout">
        <div class="creatingChapter primary-bg d-flex justify-center align-center">
            <v-container class="px-8">
                <BackBtn :url="route('subject.show', slug)"/>
                <form class="pa-8 mt-4 w-100 d-flex flex-column"  @submit.prevent="editChapter">
                    <h1 class="py-4">Editace kapitoly</h1>
                    <v-text-field
                        v-model="form.name"
                        variant="outlined"
                        label="Název"
                        :rules="[rules.required, rules.nameLength]"
                        required
                    ></v-text-field>
                    <v-text-field
                        v-model="form.perex"
                        variant="outlined"
                        label="Perex"
                        :rules="[rules.required, rules.perexLength]"
                        required
                    ></v-text-field>
                    <QuillEditor v-model:content="form.contentChapter" theme="snow" toolbar="full" content-type="html"/>
                    <v-btn type="submit"
                           color="blue"
                           class="btn d-flex my-8"
                           :class="{'w-100': $vuetify.display.smAndDown}"
                    >
                        Vytvořit!
                    </v-btn>
                    <span class="text-center text-red py-4" v-if="errors.content">{{errors.content}}</span>
                </form>
            </v-container>
        </div>
    </component>
</template>

<script setup>

import {useForm} from "@inertiajs/inertia-vue3";
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import BackBtn from "@/Frontend/Components/UI/BackBtn.vue";
const props = defineProps({slug: String, chapter: Object, errors: Object})
const form = useForm( {
    name: props.chapter.name,
    perex: props.chapter.perex,
    contentChapter: props.chapter.context,
    slug: props.chapter.slug
});
const rules = {
    required: value => !!value || 'Nutné vyplnit!',
    nameLength: value => value.length <= 20 || "Název je příliš dlouhý!",
    perexLength: value => value.length <= 50|| "Perex je příliš dlouhý!"
}
const editChapter = () => {
    form.put(route('chapter.update', {slug: props.slug, chapter: props.chapter.slug}), {
        onSuccess: () => {
        }
    });
}
</script>

<style scoped lang="scss">
:deep(.ql-container) {
    height: 40vh !important;
}
:deep(.ql-snow) {
    border: 1px solid black;
}
</style>
