<template>
    <component :is="DashboardLayout">
        <v-container>
            <div class="d-flex flex-column py-5 ga-6">
                <Breadcrumbs :items="[{title: 'Sdílení', disabled: true }]"></Breadcrumbs>
            </div>
            <v-table v-for="subject in subjects"
                     :key="subject.id">
                <thead>
                <tr>
                    <Link :href="route('subject.show',{subject: subject.slug} )"> <th colspan="6" class="font-weight-bold text-subtitle-1 text-decoration-underline"> {{
                            subject.name
                        }}
                    </th></Link>
                </tr>
                <tr v-if="subject.users.length > 0">
                    <th>
                        <v-img min-height="1em" max-height="2em" min-width="1em" :src="arrow"></v-img>
                    </th>
                    <th></th>
                    <th class="font-weight-bold">Jméno a příjmení:</th>
                    <th class="font-weight-bold">Email:</th>
                    <th class="font-weight-bold">Zvolené oprávnění</th>
                    <th class="font-weight-bold">Status:</th>
                    <th class="font-weight-bold">Nastavení:</th>
                </tr>
                </thead>
                <tbody
                    v-if="subject.users.length > 0"
                    v-for="user in subject.users"
                    :key="user.id"
                >
                <tr class="pa-8">
                    <td width="5%">
                        <v-img height="2em" min-width="1em" max-width:="2em" :src="arrow"></v-img>
                    </td>
                    <td width="2em"><v-img class="border-100"  max-height="3em" min-width="3em" max-width="3em" :src="user.image ? '/storage/' + user.image : undefinedPicture"></v-img></td>
                    <td width="20%"><p>{{ user.firstname }} {{ user.lastname }}</p></td>
                    <td width="20%"><p class="text-subtitle-2">{{ user.email }}</p></td>
                    <td width="25%">
                        <v-chip variant="flat" :color="user.permission.accepted  !== 0 ? 'green' : 'red'">
                            {{user.permission.accepted !== 0 ? 'Přijmuto' : 'Nepotvrzeno'}}
                        </v-chip>
                    </td>
                    <td width="25%">{{ user.permission.name }}</td>
                    <td>
                        <div class="d-flex ga-2">
                            <v-btn
                                variant="flat"
                                color="blue"
                                icon="mdi-pencil"
                                @click="showEditShare(user, subject)"
                            >
                            </v-btn>
                            <v-btn
                                variant="flat"
                                color="red"
                                icon="mdi-trash-can"
                                @click="deleteShare(subject.slug, user.permission.user_id)"
                            >
                            </v-btn>
                        </div>
                    </td>
                </tr>
                </tbody>
                <tbody v-else>
                <v-divider></v-divider>
                <tr>
                    <td width="2em">
                        <v-img height="2em" min-width="1em" :src="arrow"></v-img>
                    </td>
                    <td colspan="5">Nesdíleno s nikým!</td>
                </tr>
                </tbody>
            </v-table>
            <shareForm v-if="shareFormActive"
                       :active="shareFormActive"
                       :detail="usr"
                       :permission="permission"
                       @close="shareFormActive = false"></shareForm>
        </v-container>
    </component>
</template>
<script setup>
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import Breadcrumbs from "@/Frontend/Components/UI/Breadcrumbs.vue";
import {Inertia} from "@inertiajs/inertia";
import arrow from "./../../../../assets/ui/arrow-down-right.svg"
import {defineAsyncComponent, ref} from "vue";
import {Link} from "@inertiajs/inertia-vue3";
import undefinedPicture from './../../../../assets/user/Default_pfp.svg'

const shareFormActive = ref(false);
const ShareForm = defineAsyncComponent(() => import("@/Frontend/Components/shareForm.vue"));
const usr = ref({});
defineProps({subjects: Object, permission: Object})

const showEditShare = (user, subject) => {
    usr.value = {user: user, subject: subject};
    shareFormActive.value = true;
}
const deleteShare = (slug, user) => {
    Inertia.delete(route('sharing.delete', {slug: slug, user: user}))
}
</script>
<style scoped lang="scss">

</style>
