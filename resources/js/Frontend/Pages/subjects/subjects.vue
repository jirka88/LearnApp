<template>
    <component :is="DashboardLayout">
        <v-container class="pa-0">
            <div class="d-flex flex-column pa-5 gp-em-4">
                <Breadcrumbs :items="[{title: 'předměty', disabled: true }]"></Breadcrumbs>
                    <div class="btns d-flex align-center">
                        <Link :href="route('subject.create')">
                            <v-btn
                            class="bg-green">
                            Vytvořit {{$page.props.user.typeAccount === 'Osobní' ? 'sekci' : 'předmět'}}
                            </v-btn>
                        </Link>
                        <v-select
                            @update:modelValue="filtred"
                            v-model="filtr"
                            :items="items"
                            item-title="state"
                            item-value="id"
                            label="Select"
                            persistent-hint
                            return-object
                            hide-details
                            single-line
                            variant="outlined">

                        </v-select>
                    </div>
                    <v-table class="text-left">
                        <thead>
                        <tr class="pa-8">
                            <th class="font-weight-bold" v-if="$page.props.permission.view">ID:</th>
                            <th class="font-weight-bold">Název:</th>
                            <th class="font-weight-bold">Ikona:</th>
                            <th class="font-weight-bold">Počet kapitol:</th>
                            <th class="font-weight-bold" >Editace:</th>
                            <th class="font-weight-bold">Smazání:</th>
                        </tr>
                        </thead>
                        <tbody v-if="subjectsShow.length !== 0">
                            <tr class="pa-8" v-for="subjectData in subjectsShow" :key="subjectData.id">
                                <td class="font-weight-bold"  v-if="$page.props.permission.view">{{subjectData.id}}</td>
                                <td class="font-weight-bold">{{subjectData.name}}</td>
                                <td><v-chip><v-icon>{{subjectData.icon}}</v-icon></v-chip></td>
                                <td>{{subjectData.chapter_count}}</td>
                                <td>
                                    <Link :href="route('subject.edit', [subjectData.slug])">
                                        <v-btn
                                    color="green"
                                    append-icon="mdi-pencil"
                                    >Upravit!</v-btn></Link>
                                </td>
                                <td>
                                    <v-btn
                                        color="red"
                                        append-icon="mdi-delete"
                                        @click="setId(subjectData.id, subjectData.name)"
                                    >Smazat!</v-btn>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td v-if="$page.props.permission.view" class="text-center" colspan="6" >Předměty nebyly vytvořeny!</td>
                                <td v-else class="text-center" colspan="5">Předměty nebyly vytvořeny!</td>
                            </tr>
                        </tbody>
                    </v-table>
                <div class="text-center pb-8">
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
            <v-dialog
                v-model="dialog"
                persistent
                width="auto"
            >
                <v-card>
                    <v-card-title class="text-h5 text-center">
                        Opravdu si přejete smazat předmět <strong>{{subjectName}}</strong>
                    </v-card-title>
                    <v-card-text class="text-center">Tato akce je nenávratná. S mazáním předmětu dojde k smázání i všech kapitol patřící pod předmět!</v-card-text>
                    <v-card-actions class="margin-center">
                        <v-spacer></v-spacer>
                        <v-btn
                            class="bg-white"
                            @click="dialog = false"
                            size="x-large"
                        >
                            Zřušit
                        </v-btn>
                        <v-btn
                            class="bg-red"
                            @click="destroySubject(subjectId)"
                            size="x-large"
                        >
                            Smazat!
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
    </component>
</template>
<script setup>
import  {Link, useForm} from "@inertiajs/inertia-vue3";
import axios from 'axios';
import DashboardLayout from "../../layouts/DashboardLayout.vue";
import inertia from "@inertiajs/inertia";
import {markRaw, ref} from "vue";
import { useRouter } from 'vue-router';
import Breadcrumbs from "@/Frontend/Components/UI/Breadcrumbs.vue";
const router = useRouter()
const form = useForm();
const dialog = ref(false);
const subjectId = ref();
const subjectName = ref();
const filtr = ref({state: 'Výchozí', id: 'default'});
const page = ref(1);
const props = defineProps({subjects: Object, pages: Number});
const subjectsShow = ref(props.subjects);
const setId = (id, name) => {
    dialog.value = true;
    subjectId.value = id;
    subjectName.value = name;
}
const destroySubject = (id) => {
    form.delete(route('subject.destroy', id));
    dialog.value = false;
}

const fetchData = () => {
    inertia.Inertia.get(route('subject.index'),{page: page.value}, {preserveState: true, onSuccess: (response) => {
        subjectsShow.value = response.props.subjects;
    }});
}
const filtred = () => {
    const sort = filtr.value.id;

    axios.get(`/dashboard/manager/subjects/sort?sort=${sort}`)
        .then(response => {
            console.log(response.data)
            subjectsShow.value = response.data;
            router.push(`?sort=${sort}`);
        })
        .catch(error => {
            console.error(error);
        });
}
const items = markRaw(
    [{state: 'Výchozí', id: 'default'},
    {state: 'Sestupně', id: 'ASC'},
    {state: 'Vzestupně', id: 'DESC'}]
);
</script>

<style scoped lang="scss">
.btns {
    justify-content: space-between;
}
.v-select {
    max-width: 150px;
}
.v-chip {
    border-radius: 50% !important;
    height: 40px !important;
}
.v-dialog  {
    max-width: 800px;
    .v-card {
        padding: 1.5em !important;
        white-space: unset;
    }
}
</style>
