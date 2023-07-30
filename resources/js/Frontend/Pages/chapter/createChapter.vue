
<template>
    <component :is="DashboardLayout">
        <div class="creatingUser">
            <v-container class="pa-8">
                <h1>Vytvoření Kapitoly</h1>
                <form class="py-8 w-100 d-flex flex-column"  @submit.prevent="createChapter">
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
                           class="btn d-flex"
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
const props = defineProps({slug: String, errors: Object})
const form = useForm( {
    name: "",
    perex: "",
    contentChapter: "",
    slug: props.slug
});
const rules = {
    required: value => !!value || 'Nutné vyplnit!',
    nameLength: value => value.length <= 20 || "Název je příliš dlouhý!",
    perexLength: value => value.length <= 50|| "Perex je příliš dlouhý!"
}
const createChapter = () => {
    form.post(route('chapter.store', props.slug), {
        onSuccess: () => {
        }
    });
}
</script>

<style scoped lang="scss">
.creatingUser {
    height: calc(100vh - 64px);
    max-height: 100vh;
    overflow: auto;
    :deep(.v-messages__message){
        padding-bottom: 1.2em;
        text-align: left !important;
    }
    .v-btn {
        margin: 0px auto;
        padding: 2em;
    }
}
:deep(.ql-container) {
    height: 50vh !important;
}
:deep(.ql-snow) {
    border: 1px solid black;
}
</style>
