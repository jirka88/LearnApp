<template>
    <component :is="DashboardLayout">
        <v-container>
            <Breadcrumbs :items="[{title: 'Uživatelé', disabled: true }]"></Breadcrumbs>
            <div v-if="$page.props.permission.view" class="btns d-flex align-center py-8">
                <Link :href="route('adminuser.create')" data-aos="zoom-in"
                      data-aos-duration="400">
                    <v-btn
                        class="bg-green">
                        {{ $t('global.create_user') }}
                    </v-btn>
                </Link>
            </div>
            <v-dialog
                v-model="status"
                persistent
                width="auto"
            >
                <DialogDelete :Obj='activeUser' Path='adminuser.destroy' Type="uživatele"
                              Description="Se smazáním uživatele dojde i k smazání jeho vytvořených předmětů a kapitol"
                              @close="status = false"></DialogDelete>
            </v-dialog>
            <v-table class="text-left">
                <thead>
                <tr>
                    <th class="font-weight-bold">ID:</th>
                    <th class="font-weight-bold">{{ $t('userAccount.image') }}:</th>
                    <th class="font-weight-bold">{{ $t('global.name') }}:</th>
                    <th class="font-weight-bold">{{ $t('global.surname') }}:</th>
                    <th class="font-weight-bold">Email:</th>
                    <th class="font-weight-bold">Role:</th>
                    <th class="font-weight-bold">Licence:</th>
                    <th class="font-weight-bold">{{ $t('dashboard.active') }}:</th>
                    <th class="font-weight-bold">Předměty:</th>
                    <th class="font-weight-bold">{{ $t('global.setting') }}:</th>
                </tr>
                </thead>
                <tbody>
                <tr class="pa-8" v-for="user in users.data" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td class="mx-0">
                        <v-avatar>
                            <v-img
                                :src="user.image ? '/storage/' + user.image : undefinedProfilePicture"
                                alt="John"
                            ></v-img>
                        </v-avatar>
                    </td>
                    <td>{{ user.firstname }}</td>
                    <td>{{ user.lastname }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.roles.role }}</td>
                    <td>{{ user.licences.Licence }}</td>
                    <td class="text-uppercase">{{ user.active == 1 ? $t('global.yes') : $t("global.no") }}</td>
                    <td v-if="user.id == $page.props.user.id || $page.props.permission.administrator_view">
                        <Link :href="route('adminuser.subjects', user.slug)">
                            <v-btn class="bg-green">
                                {{ $t('global.show') }}
                            </v-btn>
                        </Link>
                    </td>
                    <td v-else-if="user.roles.id !== 1 && user.roles.id !== 2 && $page.props.permission.operator_view">
                        <Link :href="route('adminuser.subjects', user.slug)">
                            <v-btn class="bg-green">
                                {{ $t('global.show') }}
                            </v-btn>
                        </Link>
                    </td>
                    <td v-else></td>
                    <td v-if="$page.props.permission.administrator_view || user.id == $page.props.user.id"
                        class="d-flex align-center ga-2">
                        <Link :href="route('adminuser.edit', user.slug)">
                            <v-btn class="bg-green" icon="mdi-pencil"></v-btn>
                        </Link>
                        <v-btn v-if="user.id !== $page.props.user.id" class="bg-red" icon="mdi-trash-can"
                               @click="enableDialog(user)"></v-btn>
                    </td>
                    <td v-else-if="user.roles.id !== 1 && user.roles.id !== 2 && $page.props.permission.operator_view"
                        class="d-flex align-center ga-2">
                        <Link :href="route('adminuser.edit', user.slug)">
                            <v-btn class="bg-green" icon="mdi-pencil"></v-btn>
                        </Link>
                        <v-btn v-if="user.id !== $page.props.user.id" class="bg-red" icon="mdi-trash-can"
                               @click="enableDialog(user)"></v-btn>
                    </td>
                    <td v-else></td>
                </tr>
                </tbody>
            </v-table>
            <v-pagination
                v-model="page"
                :length="pages"

                prev-icon="mdi-menu-left"
                next-icon="mdi-menu-right"
                @update:modelValue="fetchData"
            ></v-pagination>
        </v-container>
    </component>
</template>

<script setup>
import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {Link} from "@inertiajs/inertia-vue3";
import {defineAsyncComponent, ref} from "vue";
import inertia from "@inertiajs/inertia";
import undefinedProfilePicture from './../../../../assets/user/Default_pfp.svg';
import Breadcrumbs from "@/Frontend/Components/UI/Breadcrumbs.vue";

const activeUser = ref('');
const status = ref(false);
const page = ref(1);
const props = defineProps({users: Object, pages: Object});

const DialogDelete = defineAsyncComponent(() =>
    import('@/Frontend/Components/UI/Dialog-delete.vue')
)
const enableDialog = (user) => {
    activeUser.value = user;
    status.value = true;
}
const fetchData = () => {
    inertia.Inertia.get(route('admin'), {page: page.value}, {
        preserveState: true, onSuccess: (response) => {
            props.users = response.props.users;
        }
    });
}
</script>

<style scoped lang="scss">
table {
    tr {
        height: 4em !important;

        td:last-child {
            height: inherit !important;
        }
    }
}

</style>
