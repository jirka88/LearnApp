
<template>
    <fieldset class="menus pa-8" :class="{'w-100': $vuetify.display.smAndDown}">
        <legend align="center" class="text-h5">Informace o účtě:</legend>
        <v-form ref="formResetPassword" @submit.prevent="updateUser">
            <table class="w-100">
                <tbody>
                <tr>
                    <td class="w-50">Jméno:</td>
                    <td class="w-50">
                        <v-text-field v-model="form.firstname" :rules="[rules.required,rules.lengthName]" variant="outlined"></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td class="w-50">Email:</td>
                    <td class="w-50">
                        <v-text-field v-model="form.email" :disabled="true"
                                      variant="outlined"></v-text-field>
                    </td>
                </tr>
                <tr>
                    <td class="w-50">Role:</td>
                    <td class="w-50">
                        <v-select
                            v-model="form.role"
                            :items="items"
                            :disabled="usr.role_id == 4 ? true : false"
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
                            :disabled="usr.role_id !== 4 ? false : true"
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
            <p v-if="$page.props.flash.messageUpdate" class="text-center text-green">
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
const props = defineProps({'usr': Object, 'roles': Array, 'accountTypes': Array, errors: Object});
const form = useForm({
    firstname: props.usr.firstname,
    email: props.usr.email,
    role: {state: props.usr.roles.role, id: props.usr.roles.id},
    type: {state: props.usr.account_types.type, id: props.usr.account_types.id},
})
const items = markRaw(
    props.roles.map(role => ({
        state: role.role, id: role.id
    })));
const types = markRaw(
    props.accountTypes.map(type => ({
        state: type.type, id: type.id
    })));

const updateUser = async () => {
    form.post('/dashboard/user'), {
        onSuccess: () => {
        }
    }
}
const rules = {
    required: value => !!value || 'Nutné vyplnit!',
    lengthName: v => v.length < 25 || "Příliš dlouhé jméno!"
}
</script>

<style scoped lang="scss">
.v-btn {
    margin: 1em auto;
}
</style>
