<template>
    <component :is="DashboardLayout">
        <div class="creatingUser d-flex justify-center align-center py-4">
            <v-container class="d-flex justify-center flex-column pa-8">
                <h1>{{ $t('global.create_user') }}</h1>
                <form class="pt-8 w-100 d-flex flex-column ga-1" @submit.prevent="createUser">
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
                        item-title="role"
                        item-value="id"
                        variant="outlined"
                        persistent-hint
                        return-object
                        single-line
                    ></v-select>
                    <v-select
                        v-model="form.type"
                        label="Typ účtu"
                        hint="Nastavení typ účtu uživatele"
                        :items="accountTypes"
                        item-title="type"
                        item-value="id"
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
                        item-title="Licence"
                        item-value="id"
                        variant="outlined"
                        persistent-hint
                        return-object
                        single-line
                    ></v-select>
                    <v-btn type="submit"
                           color="blue"
                           class="btn d-flex"
                           :disabled="disabledBtn"
                           :class="{'w-100': $vuetify.display.smAndDown}"
                    >
                        {{ $t('global.created') }}!
                    </v-btn>
                    <span class="text-center text-red py-4"
                          v-if="errors.email">{{ errors.email === "0" ? "" : errors.email }}</span>
                </form>
            </v-container>
        </div>
    </component>
</template>

<script setup>

import DashboardLayout from "@/Frontend/layouts/DashboardLayout.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {ref} from "vue";
import rules from "./../../rules/rules"

const props = defineProps({accountTypes: Object, roles: Object, licences: Object, errors: Object});
const form = useForm({
    firstname: "",
    lastname: "",
    email: "",
    password: "",
    type: {type: props.accountTypes[0].type, id: props.accountTypes[0].id},
    role: {role: props.roles[0].role, id: props.roles[0].id},
    licence: {Licence: props.licences[0].Licence, id: props.licences[0].id}
});
const show = ref(false);
const disabledBtn = ref(false);
const createUser = () => {
    disabledBtn.value = true;
    form.post(route('adminuser.store'), {
            onError: () => {
                disabledBtn.value = false;
            }
        }
    )
}
</script>


<style scoped lang="scss">
.creatingUser {
    min-height: calc(100vh - 64px);
    background: #4398f0 !important;
    overflow: auto;

    :deep(.v-messages__message) {
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
