<template>
    <component :is="DashboardLayout">
        <v-container>
            <div class="d-flex flex-column pa-4 gp-em-2">
                <div>
                <p class="text-h5">Předměty uživatele: {{subjects.firstname}} - {{subjects.email}}</p>
                <v-divider></v-divider>
                </div>
                <Link :href="route('adminuser.createSubject', subjects.slug)"  data-aos="zoom-in"
                      data-aos-duration="400">
                    <div class="btns">
                        <v-btn
                        class="bg-green">
                        Vytvořit předmět
                        </v-btn>
                    </div>
                </Link>
                    <v-table class="text-left">
                        <thead>
                        <tr>
                            <th class="font-weight-bold">ID:</th>
                            <th class="font-weight-bold">Název:</th>
                            <th class="font-weight-bold">Ikona:</th>
                            <th class="font-weight-bold">Počet kapitol:</th>
                            <th class="font-weight-bold">Zobrazit:</th>
                            <th class="font-weight-bold" >Editace:</th>
                            <th class="font-weight-bold">Smazání:</th>
                        </tr>
                        </thead>
                        <tbody v-if=" subjects.patritions.length !== 0">
                            <tr v-for="subjectData in subjects.patritions" :key="subjectData.id">
                                <td class="font-weight-bold">{{subjectData.id}}</td>
                                <td class="font-weight-bold">{{subjectData.name}}</td>
                                <td><v-chip><v-icon>{{subjectData.icon}}</v-icon></v-chip></td>
                                <td>{{subjectData.chapter_count}}</td>
                                <td>
                                    <Link :href="route('subject.show', subjectData.slug)">
                                        <v-btn
                                            color="green"
                                            append-icon="mdi-near-me"
                                        >Zobrazit</v-btn></Link>
                                </td>
                                <td>
                                    <Link :href="route('subject.edit', subjectData.slug)">
                                        <v-btn
                                    color="blue"
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
                                <td class="text-center" colspan="6">Předměty nebyly vytvořeny!</td>
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
    </component>
</template>
<script setup>
import {Link, useForm} from "@inertiajs/inertia-vue3";
import DashboardLayout from "../../layouts/DashboardLayout.vue";
import {ref} from "vue";
const form = useForm();
const dialog = ref(false);
const subjectId = ref();
const subjectName = ref();
const setId = (id, name) => {
    dialog.value = true;
    subjectId.value = id;
    subjectName.value = name;
}
defineProps({subjects: Object});
const destroySubject = (id) => {
    form.delete(route('subject.destroy', id));
    dialog.value = false;
}
</script>

<style scoped lang="scss">

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
