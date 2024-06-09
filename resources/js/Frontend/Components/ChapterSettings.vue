<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import BackBtn from '@/Frontend/Components/UI/BackBtn.vue'
import { useDialogDeleteStore } from '../../../states/dialogDeleteData'

const dialogDeleteStore = useDialogDeleteStore()
const props = defineProps({ chapter: Object, slug: String })
const enableDialog = () => {
    dialogDeleteStore.setDialogWithUrlParams(true, 'chapter.destroy', {
        slug: props.slug,
        chapter: props.chapter.slug
    }, false)
}
</script>

<template>
    <div class="d-flex ga-2 align-center">
        <BackBtn
            :url="route('subject.show', slug)"
            background="green"
            data-aos="zoom-in"
            data-aos-duration="400"
        />
        <Link
            v-if="chapter.partition.users[0].permission.permission_id != 1"
            :href="route('chapter.edit', { slug: slug, chapter: chapter.slug })"
            data-aos="zoom-in"
            data-aos-duration="400"
        >
            <v-btn icon="mdi-pencil" variant="flat" color="blue" size="large">
            </v-btn>
        </Link>
            <v-btn
                v-if="chapter.partition.users[0].permission.permission_id != 1"
                data-aos="zoom-in"
                data-aos-duration="400"
                icon="mdi-trash-can"
                variant="flat"
                color="red"
                size="large"
                @click="enableDialog"
            >
            </v-btn>
    </div>
</template>

<style scoped lang="scss">
div {
    background: white !important;
}
</style>
