<template>
    <AdminLayout>
        <v-container>
            <div class="d-flex flex-column pa-5 gp-em-4">
                <Link :href="route('subject.create')">
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
                            <th>Název:</th>
                            <th>Ikona:</th>
                            <th>Počet kapitol:</th>
                            <th>Nastavení:</th>
                        </tr>
                        </thead>
                        <tbody v-if="this.$page.props.user.subjects.length !== 0">
                            <tr v-for="subjectData in this.$page.props.user.subjects" :key="subjectData.id">
                                <td class="font-weight-bold">{{subjectData.name}}</td>
                                <td><v-chip><v-icon>{{subjectData.icon}}</v-icon></v-chip></td>
                                <td>0</td>
                                <td>
                                    <div class="d-flex gp-em-05"><Link :href="route('subject.edit',[subjectData.id])"> <v-btn
                                    color="green"
                                    icon="mdi-pencil"
                                    ></v-btn></Link>
                                    <v-btn
                                        color="red"
                                        icon="mdi-delete"
                                    ></v-btn>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td class="text-center" colspan="4">Předměty nebyly vytvořeny!</td>
                            </tr>
                        </tbody>
                    </v-table>
            </div>
        </v-container>
    </AdminLayout>



</template>
<script setup>
import {Link} from "@inertiajs/inertia-vue3";
import AdminLayout from "../layouts/DashboardLayout.vue";
</script>

<style scoped lang="scss">

td {
    padding-top: 1em !important;
    padding-bottom: 1em !important;
}
.v-chip {
    border-radius: 50% !important;
    height: 40px !important;
}
</style>
