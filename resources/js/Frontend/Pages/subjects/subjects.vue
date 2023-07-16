<template>
    <AdminLayout>
        <v-container>
            <div class="d-flex flex-column pa-5 gp-em-4">
                    <div class="btns d-flex align-center">
                        <Link :href="route('subject.create')">
                            <v-btn
                            class="bg-green">
                            Vytvořit předmět
                            </v-btn>
                        </Link>
                        <v-select
                            @update:modelValue="filtred"
                            v-model="filtr"
                            :items="items"
                            :disabled="this.$page.props.permission.view ? false : true"
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
                        <tr>
                            <th class="font-weight-bold">Název:</th>
                            <th class="font-weight-bold">Ikona:</th>
                            <th class="font-weight-bold">Počet kapitol:</th>
                            <th class="font-weight-bold" >Editace:</th>
                            <th class="font-weight-bold">Smazání:</th>
                        </tr>
                        </thead>
                        <tbody v-if="subjectsShow.length !== 0">
                            <tr v-for="subjectData in subjectsShow" :key="subjectData.id">
                                <td class="font-weight-bold">{{subjectData.name}}</td>
                                <td><v-chip><v-icon>{{subjectData.icon}}</v-icon></v-chip></td>
                                <td>0</td>
                                <td>
                                    <Link :href="route('subject.edit', subjectData.id)">
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
                                <td class="text-center" colspan="5">Předměty nebyly vytvořeny!</td>
                            </tr>
                        </tbody>
                    </v-table>
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
    </AdminLayout>



</template>
<script setup>
import {Link, useForm} from "@inertiajs/inertia-vue3";
import axios from 'axios';
import AdminLayout from "../../layouts/DashboardLayout.vue";
import {markRaw, ref} from "vue";
const form = useForm();
const dialog = ref(false);
const subjectId = ref();
const subjectName = ref();
const filtr = ref({state: 'Výchozí', id: 'default'});

const props = defineProps({subjects: Object});
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

const filtred = () => {
    const sort = filtr.value.id;

    axios.get(`/dashboard/manager/subjects/sort?sort=${sort}`)
        .then(response => {
            console.log(response.data)
            subjectsShow.value = response.data;
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
        text-wrap: balance;
    }
}
</style>
