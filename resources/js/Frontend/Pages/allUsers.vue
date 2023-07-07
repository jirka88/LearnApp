<template>
    <DashboardLayout>
        <v-container>
    <v-table class="text-left">
            <thead>
            <tr>
                <th class="font-weight-bold">Jméno:</th>
                <th class="font-weight-bold">Email:</th>
                <th class="font-weight-bold">Role:</th>
                <th class="font-weight-bold">Typ účtu:</th>
                <th class="font-weight-bold">Aktivní:</th>
                <th class="font-weight-bold">Předměty:</th>
                <th class="font-weight-bold" >Nastavení:</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users" :key="user.id">
                 <td>{{user.firstname}}</td>
                 <td>{{user.email}}</td>
                <td>{{user.roles.role}}</td>
                <td>{{user.account_types.type}}</td>
                <td>{{user.active == 1 ? "ANO" : "NE"}}</td>
                <td> <Link :href="route('adminuser.subjects', user.id)">
                    <v-btn class="bg-green">
                        Zobrazit
                    </v-btn>
                </Link></td>
            </tr>
            </tbody>
        </v-table>
        </v-container>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {Link} from "@inertiajs/inertia-vue3";

defineProps({users: Object});
</script>

<style lang="scss">

</style>
