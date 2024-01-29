<template>
    <component :is="DashboardLayout">
        <v-container>
            <div class="d-flex flex-column pa-5 ga-6">
                <Breadcrumbs :items="[{title: 'Sdílení', disabled: true }]"></Breadcrumbs>
            </div>
            <v-table v-for="subject in subjects.patritions"
                     :key="subject.id">
                <thead>
                <tr>
                    <th colspan="4" class="font-weight-bold text-subtitle-1"> {{ subject.name }}</th>
                </tr>
                <tr>
                    <th class="font-weight-bold">Jméno a příjmení:</th>
                    <th class="font-weight-bold">Email:</th>
                    <th class="font-weight-bold">Zvolené oprávnění</th>
                    <th class="font-weight-bold">Nastavení:</th>
                </tr>
                </thead>
                <tbody
                    v-if="subject.users.length > 0"
                    v-for="user in subject.users"
                    :key="user.id"
                >
                <tr class="pa-8">
                    <td><p>{{ user.firstname }} {{ user.lastname }}</p></td>
                    <td><p class="text-subtitle-2">{{ user.email }}</p></td>
                    <td>{{ user.permission.name }}</td>
                    <td>
                        <v-btn
                            variant="flat"
                            color="red"
                            icon="mdi-trash-can"
                            @click="deleteShare(subject.slug, user.id)"
                        >
                        </v-btn>
                    </td>
                </tr>
                </tbody>
                <tbody v-else>
                <tr>
                    <td>Nesdíleno s nikým!</td>
                </tr>
                </tbody>
            </v-table>
        </v-container>
    </component>
</template>
<script setup>
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import Breadcrumbs from "@/Frontend/Components/UI/Breadcrumbs.vue";
import {Inertia} from "@inertiajs/inertia";
defineProps({subjects: Object})

const deleteShare = (slug, user) => {
    Inertia.delete(route('sharing.delete', {slug: slug, user: user}))
}
</script>
<style scoped lang="scss">

</style>
