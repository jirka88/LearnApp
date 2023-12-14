
<template>
    <fieldset class="menus pa-8" :class="{'w-100': $vuetify.display.smAndDown}">
        <legend align="center" class="text-h5">Informace o účtě:</legend>
        <v-form ref="formResetPassword" @submit.prevent="$page.props.permission.view ?  updateAdminUser(usr.id) : updateUser(usr.id)">
            <table class="w-100">
                <tbody>
                <tr>
                    <td class="w-50">Jméno:</td>
                    <td class="w-50">
                        <v-text-field v-model="form.firstname" :rules="[rules.required,rules.lengthName]" variant="outlined"></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td class="w-50">Příjmení:</td>
                    <td class="w-50">
                        <v-text-field v-model="form.lastname" :rules="[rules.required,rules.lengthlastName]" variant="outlined"></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td class="w-50">Email:</td>
                    <td class="w-50">
                        <v-text-field v-model="form.email" :disabled="$page.props.permission.view ? false : true"
                                      variant="outlined"></v-text-field>
                    </td>
                </tr>
                <tr v-if="usr.role_id != 4 || $page.props.user.role.id == 1 || $page.props.user.role.id == 2">
                    <td class="w-50">Role:</td>
                    <td class="w-50">
                        <v-select
                            v-model="form.role"
                            :items="items"
                            :disabled="permission($page.props.permission.view, $page.props.user.role.id  )"
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
                <tr>
                    <td class="w-50">Typ účtu:</td>
                    <td class="w-50">
                        <v-select
                            v-model="form.type"
                            :items="types"
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
                <tr v-if="!$page.props.permission.view || props.usr.role_id == 4">
                    <td class="w-50">Licence:</td>
                    <td v-if="!$page.props.permission.view" class="w-50 font-weight-bold pt-2 py-6">{{props.usr.licences.Licence}}</td>
                  <td  v-else class="w-50">
                            <v-select
                                v-model="form.licences"
                                :items="licences"
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
            <p v-if="$page.props.flash.messageUpdate" class="text-center pt-4 font-weight-bold text-green">
                {{ $page.props.flash.messageUpdate }}</p>
            <v-btn type="submit"
                   color="blue"
                   class="btn d-flex"
                   :class="{'w-100': $vuetify.display.smAndDown}"
            >
                Upravit!
            </v-btn>
        </v-form>
    </fieldset>
</template>

<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import {markRaw} from "vue";
const props = defineProps({'usr': Object, 'roles': Array, 'accountTypes': Array, 'licences': Array, errors: Object});
const form = useForm({
    firstname: props.usr.firstname,
    lastname: props.usr.lastname,
    email: props.usr.email,
    role: {state: props.usr.roles.role, id: props.usr.roles.id},
    type: {state: props.usr.account_types.type, id: props.usr.account_types.id},
    licences: {state: props.usr.licences.Licence, id: props.usr.licences.id},
    active: props.usr.active == 1 ? {state: 'ANO', id: '1'} : {state: 'NE', id: '0'}
});
const items= markRaw(
    props.roles.map(role => ({
        state: role.role, id: role.id
    })));
const types = markRaw(
    props.accountTypes.map(type => ({
        state: type.type, id: type.id
    })));
const licences = markRaw(
    props.licences.map(licence => ({
        state: licence.Licence, id: licence.id
    })));
const status = markRaw([
    {state: 'ANO', id: '1'},
    {state: 'NE', id: '0'}]
);

const updateUser = async (id) => {
    form.put('/dashboard/user'), {
        onSuccess: () => {
        }
    }
}
const updateAdminUser = async(id) => {
    form.put(route('adminuser.update', id));
}
const rules = {
    required: value => !!value || 'Nutné vyplnit!',
    lengthName: v => v.length < 25 || "Příliš dlouhé jméno!",
    lengthlastName: v => v.length < 50 || "Příliš dlouhé příjmení!"
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
