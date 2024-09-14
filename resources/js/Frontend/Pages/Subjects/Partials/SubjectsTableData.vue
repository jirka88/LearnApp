<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { useDialogDeleteStore } from '../../../../../states/dialogDeleteData'

const props = defineProps({ subjects: Array | Object })

const dialogDeleteStore = useDialogDeleteStore()
const destroySubject = (subject) => {
    dialogDeleteStore.setDialog(true, subject, 'subject.destroy')
}
</script>

<template>
    <tr class="pa-8" v-for="subjectData in subjects" :key="subjectData.id">
        <td class="font-weight-bold" v-if="$page.props.permission.view">
            {{ subjectData.id }}
        </td>
        <td class="font-weight-bold">
            {{ subjectData.name }}
        </td>
        <td>
            <v-chip>
                <v-icon>{{ subjectData.icon }}</v-icon>
            </v-chip>
        </td>
        <td class="font-weight-bold">
            {{ subjectData.chapter_count }}
        </td>
        <td>
            <Link
                :href="
                    route('subject.edit', {
                        subject: subjectData.slug
                    })
                "
            >
                <v-btn color="green" append-icon="mdi-pencil"
                    >{{ $t('global.edit') }}
                </v-btn>
            </Link>
        </td>
        <td>
            <v-btn
                color="red"
                append-icon="mdi-delete"
                @click="destroySubject(subjectData)"
                >{{ $t('global.delete') }}!
            </v-btn>
        </td>
    </tr>
</template>

<style scoped lang="scss"></style>
