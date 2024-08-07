<template>
    <component :is="DashboardLayout">
        <div
            class="creatingChapter primary-bg d-flex justify-center align-center"
        >
            <v-container class="px-8">
                <BackBtn :url="route('subject.show', chapter.partition.slug)" />
                <form
                    class="pa-8 mt-4 w-100 d-flex flex-column"
                    @submit.prevent="editChapter"
                >
                    <h1 class="py-4">Editace kapitoly</h1>
                    <v-text-field
                        v-model="form.name"
                        variant="outlined"
                        :label="$t('global.name')"
                        :rules="[rules.required, rules.chapterNameLength]"
                        :error="form.errors.name"
                        :error-messages="form.errors.name"
                        required
                    ></v-text-field>
                    <v-text-field
                        v-model="form.perex"
                        variant="outlined"
                        label="Perex"
                        :rules="[rules.perexLength]"
                    ></v-text-field>
                    <v-no-ssr>
                        <QuillEditor
                            v-model:content="form.contentChapter"
                            theme="snow"
                            toolbar="full"
                            content-type="html"
                        />
                    </v-no-ssr>
                    <v-btn
                        type="submit"
                        color="blue"
                        class="btn d-flex my-8"
                        :class="{ 'w-100': $vuetify.display.smAndDown }"
                    >
                        {{ $t('global.created') }}!
                    </v-btn>
                    <span
                        class="text-center text-red py-4"
                        v-if="errors.content"
                        >{{ errors.content }}</span
                    >
                </form>
            </v-container>
        </div>
    </component>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/Frontend/layouts/DashboardLayout.vue'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import BackBtn from '@/Frontend/Components/UI/BackBtn.vue'
import rules from './../../rules/rules'

const props = defineProps({ slug: String, chapter: Object, errors: Object })
const form = useForm({
    name: props.chapter.name,
    perex: props.chapter.perex,
    contentChapter: props.chapter.context,
    slug: props.chapter.slug
})
const editChapter = () => {
    form.put(
        route('chapter.update', {
            slug: props.slug,
            chapter: props.chapter.slug
        }),
        {
            onSuccess: () => {}
        }
    )
}
</script>

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
