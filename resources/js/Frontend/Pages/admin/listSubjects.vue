<template>
    <component :is="DashboardLayout">
        <v-container>
            <div class="d-flex flex-column pa-4 ga-4">
                <div>
                    <p class="text-h5">
                        Předměty uživatele: {{ subjects.firstname }} -
                        {{ subjects.email }}
                    </p>
                    <v-divider></v-divider>
                </div>
                <Link :href="route('adminuser.createSubject', subjects.slug)">
                    <div class="btns">
                        <v-btn
                            data-aos="zoom-in"
                            data-aos-duration="400"
                            class="bg-green"
                        >
                            Vytvořit předmět
                        </v-btn>
                    </div>
                </Link>
                <v-table class="text-left">
                    <thead>
                        <tr>
                            <th class="font-weight-bold">ID:</th>
                            <th class="font-weight-bold">
                                {{ $t('global.name') }}:
                            </th>
                            <th class="font-weight-bold">Ikona:</th>
                            <th class="font-weight-bold">Počet kapitol:</th>
                            <th class="font-weight-bold">Zobrazit:</th>
                            <th class="font-weight-bold">Editace:</th>
                            <th class="font-weight-bold">Smazání:</th>
                        </tr>
                    </thead>
                    <tbody v-if="subjects.patritions.length !== 0">
                        <tr
                            v-for="subjectData in subjects.patritions"
                            :key="subjectData.id"
                        >
                            <td class="font-weight-bold">
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
                            <td>{{ subjectData.chapter_count }}</td>
                            <td>
                                <Link
                                    :href="
                                        route('subject.show', subjectData.slug)
                                    "
                                >
                                    <v-btn
                                        color="green"
                                        append-icon="mdi-near-me"
                                        >Zobrazit
                                    </v-btn>
                                </Link>
                            </td>
                            <td>
                                <Link
                                    :href="
                                        route('subject.edit', subjectData.slug)
                                    "
                                >
                                    <v-btn color="blue" append-icon="mdi-pencil"
                                        >{{ $t('global.edit') }}
                                    </v-btn>
                                </Link>
                            </td>
                            <td>
                                    <v-btn
                                        color="red"
                                        append-icon="mdi-delete"
                                        @click="destroySubject(subjectData)"
                                        >Smazat!
                                    </v-btn>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td class="text-center" colspan="7">
                                Předměty nebyly vytvořeny!
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </div>
        </v-container>
        <v-row justify="center"> </v-row>
    </component>
</template>
<script setup>
import { Link} from '@inertiajs/inertia-vue3'
import DashboardLayout from '../../layouts/DashboardLayout.vue'
import { useDialogDeleteStore } from '../../../../states/dialogDeleteData'

defineProps({ subjects: Object })

const dialogDeleteStore = useDialogDeleteStore()
const destroySubject = (subject) => {
    dialogDeleteStore.setDialog(true, subject, 'subject.destroy')
}
</script>

<style scoped lang="scss">
.v-chip {
    border-radius: 50% !important;
    height: 40px !important;
}

.v-dialog {
    max-width: 800px;

    .v-card {
        padding: 1.5em !important;
        white-space: unset;
        text-wrap: balance;
    }
}
</style>
