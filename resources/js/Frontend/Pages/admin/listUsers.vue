<template>
    <DashboardLayout>
        <v-container>
            <v-dialog
                v-model="status"
                persistent
                width="auto"
            >
                <DialogDelete :Obj='activeUser' Path='adminuser.destroy' Type="uživatele" Description="Se smazáním uživatele dojde i k smazání jeho vytvořených předmětů a kapitol" @close="status = false"></DialogDelete>
            </v-dialog>
            <v-table class="text-left">
                <thead>
                <tr>
                    <th class="font-weight-bold">Jméno:</th>
                    <th class="font-weight-bold">Email:</th>
                    <th class="font-weight-bold">Role:</th>
                    <th class="font-weight-bold">Typ účtu:</th>
                    <th class="font-weight-bold">Aktivní:</th>
                    <th class="font-weight-bold">Předměty:</th>
                    <th class="font-weight-bold">Nastavení:</th>
                </tr>
                </thead>
                <tbody>
                <tr class="pa-2" v-for="user in users" :key="user.id">
                    <td>{{ user.firstname }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.roles.role }}</td>
                    <td>{{ user.account_types.type }}</td>
                    <td>{{ user.active == 1 ? "ANO" : "NE" }}</td>
                    <td v-if="user.id != this.$page.props.user.id || user.roles.id !== 1">
                        <Link :href="route('adminuser.subjects', user.id)">
                            <v-btn class="bg-green">
                                Zobrazit
                            </v-btn>
                        </Link>
                    </td>
                    <td v-else></td>
                    <td v-if="user.roles.id !== 1" class="d-flex gp-em-05">
                        <Link :href="route('adminuser.edit', user.id)">
                            <v-btn class="bg-green" icon="mdi-pencil"></v-btn>
                        </Link>
                            <v-btn v-if="user.id != this.$page.props.user.id"  class="bg-red" icon="mdi-trash-can" @click="enableDialog(user)"></v-btn>
                    </td>
                    <td v-else></td>
                </tr>
                </tbody>
            </v-table>
        </v-container>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {Link} from "@inertiajs/inertia-vue3";
import {ref} from "vue";
import DialogDelete from "@/Frontend/Components/UI/Dialog-delete.vue";
const activeUser = ref('');
const status = ref(false);
defineProps({users: Object});
const enableDialog = (user) => {
    activeUser.value = user;
    status.value = true;
}
</script>

<style lang="scss">

</style>
