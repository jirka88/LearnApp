<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { useDialogDeleteStore } from '../../../states/dialogDeleteData'

const props = defineProps({ chapter: Object, subject: Object, key: Number })

const dialogDeleteStore = useDialogDeleteStore()
const enableDialog = (chapter) => {
    dialogDeleteStore.setDialogWithUrlParams(true, 'chapter.destroy', {
        slug: props.subject.slug,
        chapter: chapter.slug
    })
}
</script>

<template>
    <v-card
        data-aos="zoom-in"
        data-aos-delay="200"
        data-aos-duration="300"
        data-aos-anchor-placement="top-bottom"
        class="pa-2 elevation-20"
        min-width="15em"
        :max-width="$vuetify.display.smAndDown ? '' : '30em'"
    >
        <v-card-text>
            <p class="text-h4 font-weight-bold text--primary py-4">
                {{ chapter.name }}
            </p>
            <div class="text--primary">{{ chapter.perex }}<br /></div>
        </v-card-text>
        <v-card-actions class="flex-wrap justify-end align-center ga-2">
            <v-btn
                v-if="Number(subject.permission.permission_id) !== 1"
                icon="mdi-trash-can"
                variant="flat"
                color="red"
                @click="enableDialog(chapter)"
            >
            </v-btn>
            <Link
                v-if="Number(subject.permission.permission_id) !== 1"
                :href="
                    route('chapter.edit', {
                        slug: subject.slug,
                        chapter: chapter.slug
                    })
                "
            >
                <v-btn icon="mdi-pencil" variant="flat" color="blue"> </v-btn>
            </Link>
            <Link
                :href="
                    route('chapter.show', {
                        chapter: chapter.slug,
                        slug: subject.slug
                    })
                "
            >
                <v-btn icon="mdi-near-me" variant="flat" color="green"> </v-btn>
            </Link>
        </v-card-actions>
    </v-card>
</template>

<style scoped lang="scss">
.v-card-actions {
    .v-btn {
        border-radius: 0.5em !important;
    }
}
</style>
