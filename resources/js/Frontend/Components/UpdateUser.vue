
<template>
    <fieldset class="menus pa-8" :class="{'w-100': $vuetify.display.smAndDown}">
        <legend align="center" class="text-h5">{{$t('userAccount.information_account')}}:</legend>
        <v-form ref="formResetPassword" @submit.prevent="$page.props.permission.view ?  updateAdminUser(usr.id) : updateUser(usr.id)">
            <table class="w-100">
                <tbody>
                <tr>
                    <td>{{$t('global.name')}}:</td>
                    <td >
                        <v-text-field v-model="form.firstname" :rules="[rules.required,rules.lengthName]" variant="outlined"></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td>{{$t('global.surname')}}:</td>
                    <td >
                        <v-text-field v-model="form.lastname" :rules="[rules.required,rules.lengthlastName]" variant="outlined"></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td >Email:</td>
                    <td >
                        <v-text-field v-model="form.email" :disabled="$page.props.permission.view ? false : true"
                                      variant="outlined"></v-text-field>
                    </td>
                </tr>
                <tr v-if="usr.role_id != 4 || $page.props.user.role.id == 1 || $page.props.user.role.id == 2">
                    <td>Role:</td>
                    <td >
                        <v-select
                            v-model="form.role"
                            :items="roles"
                            :disabled="permission($page.props.permission.view, $page.props.user.role.id )"
                            item-title="role"
                            item-value="id"
                            label="Select"
                            persistent-hint
                            return-object
                            single-line
                            variant="outlined"
                        ></v-select>
                    </td>
                </tr>
                <tr>
                    <td >Typ účtu:</td>
                    <td >
                        <v-select
                            v-model="form.type"
                            :items="accountTypes"
                            item-title="type"
                            item-value="id"
                            label="Select"
                            persistent-hint
                            return-object
                            single-line
                            variant="outlined"
                        ></v-select>
                    </td>
                </tr>
                <tr v-if="!$page.props.permission.view || props.usr.role_id == 4">
                    <td class="w-50">Licence:</td>
                    <td v-if="!$page.props.permission.view" class="w-50 font-weight-bold pt-2 py-6">{{props.usr.licences.Licence}}</td>
                  <td  v-else class="w-50">
                            <v-select
                                v-model="form.licences"
                                :items="licences"
                                item-title="Licence"
                                item-value="id"
                                label="Select"
                                persistent-hint
                                return-object
                                single-line
                                variant="outlined"
                            ></v-select>
                        </td>
                </tr>
                <tr v-if="$page.props.permission.view && $page.props.user.id !== usr.id">
                    <td class="w-50">Aktivní:</td>
                    <td  class="w-50">
                        <v-select
                            v-model="form.active"
                            :items="status"
                            item-title="state"
                            item-value="id"
                            label="Select"
                            persistent-hint
                            return-object
                            single-line
                            variant="outlined"
                        ></v-select>
                    </td>
                </tr>
                </tbody>
            </table>
            <Toastify v-if="isActiveToast" :text="statusToast ? $page.props.flash.message : 'Nastala chyba!'" :variant="statusToast ? 'success' : 'error'" :time="3000" @close="isActiveToast = false"></Toastify>
            <v-btn type="submit"
                   color="blue"
                   class="btn d-flex"
                   :class="{'w-100': $vuetify.display.smAndDown}"
            >
                {{$t('global.edit')}}
            </v-btn>
        </v-form>
    </fieldset>
</template>

<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import {markRaw} from "vue";
import {isActiveToast, statusToast, toastShow, toastStatus} from "@/Toast";
import Toastify from "@/Frontend/Components/UI/Toastify.vue";
import rules from "./../rules/rules"
const props = defineProps({'usr': Object, 'roles': Array, 'accountTypes': Array, 'licences': Array});
const form = useForm({
    firstname: props.usr.firstname,
    lastname: props.usr.lastname,
    email: props.usr.email,
    role: {role: props.usr.roles.role, id: props.usr.roles.id},
    type: {type: props.usr.account_types.type, id: props.usr.account_types.id},
    licences: {Licence: props.usr.licences.Licence, id: props.usr.licences.id},
    active: props.usr.active == 1 ? {state: 'ANO', id: '1'} : {state: 'NE', id: '0'}
});

const status = markRaw([
    {state: 'ANO', id: '1'},
    {state: 'NE', id: '0'}]
);

const updateUser = async (id) => {
    form.put('/dashboard/user'), {
        onSuccess: () => {
            toastShow(true);
            toastStatus(true);
        },
        onError: () =>{
            toastShow(true);
            toastStatus(false);
        }
    }
}
const updateAdminUser = async(id) => {
    form.put(route('adminuser.update', id), {
        onSuccess: () => {
            toastShow(true);
            toastStatus(true);
        },
        onError: () => {
            toastShow(true);
            toastStatus(false);
        }
    })
}
const permission = (permissionView, userId) => {
    if(permissionView) {
        return props.usr.roles.id === 1;
    }
    return true;
}
</script>

<style scoped lang="scss">
.v-btn {
    padding: 1.6em;
    margin: 1em auto;
}
</style>
