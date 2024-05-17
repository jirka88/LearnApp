<template>
    <component :is="DashboardLayout">
        <v-container class="pa-0">
            <div class="d-flex flex-column pa-5 ga-6">
                <Breadcrumbs :items="[{title: 'předměty', disabled: true }]"></Breadcrumbs>
                <div class="btns d-flex align-center justify-space-between">
                    <Link :href="route('subject.create')">
                        <v-btn
                            class="bg-green">
                            {{ $t('global.created') }}
                            {{ $page.props.user.typeAccount === 'Osobní' ? 'sekci' : 'předmět' }}
                        </v-btn>
                    </Link>
                    <SortSelect :disabled="subjectsShow.length === 0" @filtr="filtred"></SortSelect>
                </div>
                <v-table class="text-left">
                    <thead>
                    <tr class="pa-8">
                        <th class="font-weight-bold" v-if="$page.props.permission.view">ID:</th>
                        <th class="font-weight-bold">{{ $t('global.name') }}:</th>
                        <th class="font-weight-bold">Ikona:</th>
                        <th class="font-weight-bold">{{ $t('global.chapter_count') }}:</th>
                        <th class="font-weight-bold">Editace:</th>
                        <th class="font-weight-bold">Smazání:</th>
                    </tr>
                    </thead>
                    <tbody v-if="subjectsShow.length !== 0 && !loading">
                    <tr class="pa-8" v-for="subjectData in subjectsShow" :key="subjectData.id">
                        <td class="font-weight-bold" v-if="$page.props.permission.view">{{ subjectData.id }}</td>
                        <td class="font-weight-bold">{{ subjectData.name }}</td>
                        <td>
                            <v-chip>
                                <v-icon>{{ subjectData.icon }}</v-icon>
                            </v-chip>
                        </td>
                        <td class="font-weight-bold">{{ subjectData.chapter_count }}</td>
                        <td>
                            <Link :href="route('subject.edit',{subject: subjectData.slug})">
                                <v-btn
                                    color="green"
                                    append-icon="mdi-pencil"
                                >{{ $t('global.edit') }}
                                </v-btn>
                            </Link>
                        </td>
                        <td>
                            <v-btn
                                color="red"
                                append-icon="mdi-delete"
                                @click="setId(subjectData.id, subjectData.name)"
                            >{{ $t('global.delete') }}!
                            </v-btn>
                        </td>
                    </tr>
                    </tbody>
                    <tbody v-else-if="loading">
                    <TableSkeleton v-for="n in subjectsShow" :countTd="$page.props.user.role.id === 4 ? 5 : 6"
                                   :key="n.id">

                    </TableSkeleton>
                    </tbody>
                    <tbody v-else>
                    <tr>
                        <td v-if="$page.props.permission.view" class="text-center" colspan="6">Předměty nebyly
                            vytvořeny!
                        </td>
                        <td v-else class="text-center" colspan="5">Předměty nebyly vytvořeny!</td>
                    </tr>
                    </tbody>
                </v-table>
                <div v-if="pages" class="text-center pb-8">
                    <v-pagination
                        v-model="page"
                        :length="pages"

                        prev-icon="mdi-menu-left"
                        next-icon="mdi-menu-right"
                        @update:modelValue="fetchData"
                    ></v-pagination>
                </div>
            </div>
        </v-container>
        <v-row justify="center">
            <DialogDelete
                v-if="dialog"
                v-model:dialog="dialog"
                :subject="subject">
                <v-btn
                    class="bg-red"
                    @click="destroySubject()"
                    size="x-large"
                >
                    {{ $t('global.delete') }}
                </v-btn>
            </DialogDelete>
        </v-row>
    </component>
</template>
<script setup>
import {Link} from "@inertiajs/inertia-vue3";
import axios from 'axios';
import DashboardLayout from "../../layouts/DashboardLayout.vue";
import {Inertia} from "@inertiajs/inertia";
import {defineAsyncComponent, markRaw, onMounted, ref, watch} from "vue";
import Breadcrumbs from "@/Frontend/Components/UI/Breadcrumbs.vue";
import {useUrlSearchParams} from '@vueuse/core';
import SortSelect from "@/Frontend/Components/SortSelect.vue";

const dialog = ref(false);
const DialogDelete = defineAsyncComponent(() => import("@/Frontend/Components/DialogBeforeDeleteSubject.vue"));
const TableSkeleton = defineAsyncComponent(() => import("@/Frontend/Components/Loading/TableSkeleton.vue"));
const props = defineProps({
    subjects: Object,
    pages: Number,
    sort: String,
});

const subject = ref({
    subjectName: '',
    subjectId: ''
});
const page = ref(props.subjects.current_page);
const subjectsShow = ref(props.subjects.data);
const loading = ref(false);
const filtrGlobal = ref('');


const setId = (id, name) => {
    dialog.value = true;
    subject.value.subjectId = id;
    subject.value.subjectName = name;
}
const destroySubject = () => {
    Inertia.delete(route('subject.destroy', subject.value.subjectId), {
        preserveState: true, onSuccess: (response) => {
            subjectsShow.value = response.props.subjects.data;
            dialog.value = false;
        }
    });
}

const fetchData = () => {
    Inertia.get(route('subject.index'), {page: page.value, sort: filtrGlobal.value}, {
        preserveState: true, onSuccess: (response) => {
            subjectsShow.value = response.props.subjects.data;
            page.value = response.props.subjects.current_page;
        },
    });

}
const filtred = async (filtr) => {
    loading.value = true;
    const params = useUrlSearchParams('history')
    params.sort = filtr.sort + ',' + filtr.id;
    filtrGlobal.value = filtr.sort + ',' + filtr.id;
    await axios.get(`/dashboard/manager/subjects/sort?sort=${filtr.sort},${filtr.id}`)
        .then(response => {
            subjectsShow.value = response.data.search.data;
            params.page = 1;
            page.value = 1;
            loading.value = false;
        })
}
</script>

<style scoped lang="scss">

.v-select {
    max-width: 180px;
}

.v-chip {
    border-radius: 50% !important;
    height: 40px !important;
}

.v-dialog {
    max-width: 800px;

    .v-card {
        padding: 1.5em !important;
        white-space: unset;
    }
}
</style>
