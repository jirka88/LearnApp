<template>
    <component :is="DashboardLayout">
            <div class="creatingUser d-flex justify-center align-center flex-column">
                <v-container class="d-flex justify-center flex-column pa-8">
            <h1>Vytvoření uživatele</h1>
            <form class="py-8 w-100 d-flex flex-column gp-em-05"  @submit.prevent="createUser">
                <v-text-field
                    v-model="form.firstname"
                    prepend-inner-icon="mdi-account"
                    variant="outlined"
                    label="Jméno"
                    :rules="[rules.required, rules.firstnameLength]"
                    required
                ></v-text-field>
                <v-text-field
                    v-model="form.lastname"
                    prepend-inner-icon="mdi-account"
                    variant="outlined"
                    label="Příjmení"
                    :rules="[rules.required, rules.lastnameLength]"
                    required
                ></v-text-field>
                <v-text-field
                    v-model="form.email"
                    prepend-inner-icon="mdi-email"
                    variant="outlined"
                    label="E-mail"
                    :rules="[rules.email, rules.required]"
                    required
                ></v-text-field>
                <v-text-field
                    v-model="form.password"
                    :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="show ? 'text' : 'password'"
                    prepend-inner-icon="mdi-lock"
                    variant="outlined"
                    label="Heslo"
                    :rules="[rules.required, rules.password]"
                    @click:append="show = !show"
                ></v-text-field>
                <v-select
                    v-model="form.role"
                    label="Role"
                    :items="roles"
                    hint="Nastavení role uživatele"
                    item-title="state"
                    item-value="value"
                    variant="outlined"
                    persistent-hint
                    return-object
                    single-line
                ></v-select>
                <v-select
                    v-model="form.type"
                    label="Typ účtu"
                    hint="Nastavení typ účtu uživatele"
                    :items="types"
                    item-title="state"
                    item-value="value"
                    variant="outlined"
                    persistent-hint
                    return-object
                    single-line
                ></v-select>
                <v-select
                    v-model="form.licence"
                    label="Licence"
                    hint="Nastavení licence uživatele"
                    :items="licences"
                    item-title="state"
                    item-value="value"
                    variant="outlined"
                    persistent-hint
                    return-object
                    single-line
                ></v-select>
                <v-btn type="submit"
                       color="blue"
                       class="btn d-flex"
                       :class="{'w-100': $vuetify.display.smAndDown}"
                >
                    Vytvořit!
                </v-btn>
                {{licences}}
                <span class="text-center text-red py-4" v-if="errors.email">{{errors.email === "0" ? "" : errors.email}}</span>
            </form>
                </v-container>
            </div>
    </component>
</template>

<script setup>

import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {markRaw, ref} from "vue";

const props = defineProps({accountTypes: Object, roles: Object, licences: Object, errors: Object});
const form = useForm( {
    firstname: "",
    lastname: "",
    email: "",
    password: "",
    type: {state: "Osobní", id: 1},
    role: {state: "Uživatel", id: 4},
    licence: {state: "Standart", id: 1}
});
const show = ref(false);
const types = markRaw(
    props.accountTypes.map(type => ({
        state: type.type, id: type.id
    })));
const roles = markRaw(
    props.roles.map(role => ({
        state: role.role, id: role.id
    })));
const licences = markRaw(
    props.licences.map(licenc => ({
        state: licenc.Licence, id: licenc.id
    })));
const rules = {
    required: value => !!value || 'Nutné vyplnit!',
    firstnameLength: v => v.length < 25 || 'Jméno je příliš dlouhé!',
    lastnameLength: v => v.length < 50 || 'Příjmení je příliš dlouhé!',
    email: v => /^(([^<>()[\]\\.,;:\s@']+(\.[^<>()\\[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(v) || 'E-mail musí být validní!',
    password: v => {
        const missingElements = [];
        if(v.length < 8) {
            missingElements.push('více než 8 znaků');
        }
        if (!/(?=.*\d)/.test(v)) {
            missingElements.push('číslici');
        }
        if (!/[!@#$%^&*]/.test(v)) {
            missingElements.push('speciální znak');
        }
        if (!/(?=.*[a-z])/.test(v)) {
            missingElements.push('malé písmeno');
        }
        if (!/(?=.*[A-Z])/.test(v)) {
            missingElements.push('velké písmeno');
        }
        if (missingElements.length > 0) {
            return `Heslo musí obsahovat ${missingElements.join(', ')}!`;
        }
        else {
            return true;
        }
    },
}
const createUser = () => {
    form.post(route('adminuser.store'), {
        onSuccess: () => {
        }
    })
}
</script>


<style scoped lang="scss">
    .creatingUser {
        height: calc(100vh - 64px);
        background: #4398f0 !important;
        overflow: auto;
        :deep(.v-messages__message){
            padding-bottom: 1.2em;
            text-align: left !important;
        }
        .v-btn {
            margin: 0 auto;
        }
    }
.v-container {
    max-width: 540px !important;
    background: white;
    border-radius: 24px;
}
 </style>
